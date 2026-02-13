<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedPhone;
use App\Models\ExclusiveLandingConfig;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ExclusiveLandingController extends Controller
{
    private const RATE_LIMIT_KEY = 'exclusive-landing-phone';
    private const RATE_LIMIT_ATTEMPTS = 5;
    private const RATE_LIMIT_DECAY = 60; // 1 min

    /**
     * Show the phone gate (or redirect to landing / expired / disabled).
     */
    public function gate(Request $request)
    {
        $config = ExclusiveLandingConfig::current();
        if (!$config) {
            return $this->viewDisabled();
        }
        if (!$config->isAvailable()) {
            if ($config->isExpired()) {
                return $this->viewExpired($config);
            }
            return $this->viewDisabled();
        }
        if ($request->session()->get('exclusive_landing_validated')) {
            return redirect()->route('exclusive-landing.index');
        }
        return response()
            ->view('exclusive-landing.gate', ['config' => $config])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    /**
     * Validate phone and allow access or show restricted message.
     */
    public function validatePhone(Request $request)
    {
        $config = ExclusiveLandingConfig::current();
        if (!$config || !$config->isAvailable()) {
            if ($config && $config->isExpired()) {
                return redirect()->route('exclusive-landing.gate')->with('error', __('Campaign has ended.'));
            }
            return redirect()->route('exclusive-landing.gate');
        }

        $key = self::RATE_LIMIT_KEY . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, self::RATE_LIMIT_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'phone' => ['Demasiados intentos. Intenta de nuevo en un minuto.'],
            ]);
        }

        $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:20'],
        ], [], ['phone' => 'número telefónico']);

        $normalized = AuthorizedPhone::normalizePhone($request->input('phone'));
        if (strlen($normalized) < 10) {
            throw ValidationException::withMessages([
                'phone' => ['El número debe incluir lada (ej. 55 1234 5678).'],
            ]);
        }

        RateLimiter::hit($key, self::RATE_LIMIT_DECAY);

        if (!AuthorizedPhone::isAuthorized($request->input('phone'))) {
            return view('exclusive-landing.restricted', [
                'config' => $config,
                'contact_phone' => $config->contact_phone ?? '55 0000 0000',
                'restricted_message' => $config->restricted_message,
            ]);
        }

        $request->session()->put('exclusive_landing_validated', true);
        $request->session()->put('exclusive_landing_validated_at', now()->toDateTimeString());

        return redirect()->route('exclusive-landing.index');
    }

    /**
     * Logout from exclusive landing (clear session).
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['exclusive_landing_validated', 'exclusive_landing_validated_at', 'exclusive_flow']);
        return redirect()->route('exclusive-landing.gate');
    }

    /**
     * Show exclusive products with filters.
     */
    public function index(Request $request)
    {
        $config = ExclusiveLandingConfig::current();
        if (!$config) {
            return $this->viewDisabled();
        }
        if (!$config->isAvailable()) {
            if ($config->isExpired()) {
                return $this->viewExpired($config);
            }
            return $this->viewDisabled();
        }
        if (!$request->session()->get('exclusive_landing_validated')) {
            return redirect()->route('exclusive-landing.gate');
        }

        $request->session()->put('exclusive_flow', true);

        $query = Product::query()
            ->where('is_active', true)
            ->where('is_exclusive_content', true)
            ->with(['images', 'category', 'subcategory', 'variants']);

        // Stock: at least one variant with stock or product stock
        $query->where(function ($q) {
            $q->whereHas('variants', fn ($v) => $v->where('stock', '>', 0))
                ->orWhere(function ($q2) {
                    $q2->whereDoesntHave('variants')->where('stock', '>', 0);
                });
        });

        if ($config->show_filter_category && $request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($config->show_filter_type && $request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }
        if ($config->show_filter_price) {
            if ($request->filled('min_price')) {
                $min = (float) $request->min_price;
                $query->whereRaw('COALESCE(sale_price, price) >= ?', [$min]);
            }
            if ($request->filled('max_price')) {
                $max = (float) $request->max_price;
                $query->whereRaw('COALESCE(sale_price, price) <= ?', [$max]);
            }
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate(20)->withQueryString();

        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name')
            ->get();

        $subcategories = Category::where('is_active', true)
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()
            ->view('exclusive-landing.index', [
            'config' => $config,
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories,
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    protected function viewDisabled()
    {
        return response()->view('exclusive-landing.disabled', [], 403);
    }

    protected function viewExpired(ExclusiveLandingConfig $config)
    {
        return view('exclusive-landing.expired', [
            'config' => $config,
            'expired_message' => $config->expired_message,
        ]);
    }

    /**
     * Show exclusive product detail (add to cart, stays in exclusivo flow).
     */
    public function product(Request $request, string $slug)
    {
        $config = ExclusiveLandingConfig::current();
        if (!$config || !$config->isAvailable()) {
            return $config && $config->isExpired() ? $this->viewExpired($config) : $this->viewDisabled();
        }

        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_exclusive_content', true)
            ->with(['images', 'category', 'subcategory', 'variants'])
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->where('is_active', true)
            ->where('is_exclusive_content', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['images', 'variants'])
            ->where(function ($q) {
                $q->whereHas('variants', fn ($v) => $v->where('stock', '>', 0))
                    ->orWhere(function ($q2) {
                $q2->whereDoesntHave('variants')->where('stock', '>', 0);
            });
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        return response()
            ->view('exclusive-landing.product', compact('config', 'product', 'relatedProducts'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
