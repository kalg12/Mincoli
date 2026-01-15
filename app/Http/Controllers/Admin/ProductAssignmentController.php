<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAssignment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductAssignment::with(['user', 'product.category', 'product.variants']);

        // Filter by User/Responsible
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by Product Category
        if ($request->filled('category_id')) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        // Filter by LOB
        if ($request->filled('lob')) {
            $query->where('partner_lob', $request->lob);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest('assigned_at')->paginate(50)->withQueryString();

        // Get all for summary (respecting filters) - Clone the query before pagination
        $summaryQuery = ProductAssignment::with(['user', 'product.category', 'product.variants']);
        
        // Apply same filters for summary
        if ($request->filled('user_id')) $summaryQuery->where('user_id', $request->user_id);
        if ($request->filled('category_id')) $summaryQuery->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        if ($request->filled('date_from')) $summaryQuery->whereDate('assigned_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $summaryQuery->whereDate('assigned_at', '<=', $request->date_to);
        if ($request->filled('lob')) $summaryQuery->where('partner_lob', $request->lob);
        if ($request->filled('status')) $summaryQuery->where('status', $request->status);
        
        $allAssignments = $summaryQuery->get();
        
        // Calculate Corte Summary with real data
        $corteSummary = [
            'iva' => $allAssignments->sum('iva_amount'),
            'partners' => $allAssignments->groupBy('partner_lob')
                ->map(fn($group) => $group->sum('base_price'))
                ->sortDesc()
        ];

        // Get filter options
        $users = \App\Models\User::whereIn('role', ['employee', 'admin'])->orderBy('name')->get();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $lobs = ProductAssignment::select('partner_lob')->distinct()->whereNotNull('partner_lob')->pluck('partner_lob');

        // Get paid orders from the filtered date range for suggestions
        $paidOrders = collect();
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $orderQuery = \App\Models\Order::with(['customer', 'items.product'])
                ->whereIn('status', ['paid', 'partially_paid']);
            
            if ($request->filled('date_from')) {
                $orderQuery->whereDate('placed_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $orderQuery->whereDate('placed_at', '<=', $request->date_to);
            }
            
            $paidOrders = $orderQuery->latest('placed_at')->limit(20)->get();
        }

        return view('admin.assignments.index', compact(
            'assignments', 
            'corteSummary', 
            'users', 
            'categories', 
            'lobs',
            'paidOrders'
        ));
    }

    public function create()
    {
        $users = User::whereIn('role', ['employee', 'admin'])->get();
        $products = Product::where('is_active', true)->with('variants')->get();
        return view('admin.assignments.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assigned_at' => 'required|date',
            'assignments' => 'required|array|min:1',
            'assignments.*.user_id' => 'required|exists:users,id',
            'assignments.*.product_id' => 'required|exists:products,id',
            'assignments.*.quantity' => 'required|integer|min:1',
            'assignments.*.unit_price' => 'required|numeric|min:0',
            'assignments.*.partner_lob' => 'nullable|string|max:255',
        ]);

        foreach ($request->assignments as $data) {
            $precio = $data['unit_price']; // This is 'Precio' in Excel
            $pago = round($precio / 1.16, 0); // 'Pago' in Excel
            $iva = $precio - $pago;         // 'IVA' in Excel

            ProductAssignment::create([
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'quantity' => $data['quantity'],
                'base_price' => $pago,
                'unit_price' => $precio,
                'iva_amount' => $iva,
                'total_amount' => $data['quantity'] * $precio,
                'partner_lob' => $data['partner_lob'],
                'status' => 'quotation', // Amarillo inicial
                'assigned_at' => $request->assigned_at,
            ]);
        }

        return redirect()->route('dashboard.assignments.index')->with('success', 'Asignaciones creadas correctamente.');
    }

    public function updateStatus(Request $request, ProductAssignment $assignment)
    {
        $request->validate([
            'status' => 'required|in:quotation,paid_customer,paid_partner,deferred,incident',
        ]);

        $assignment->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function exportPdf(Request $request)
    {
        $query = ProductAssignment::with(['user', 'product.category']);

        // Apply same filters as index
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('category_id')) $query->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        if ($request->filled('date_from')) $query->whereDate('assigned_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('assigned_at', '<=', $request->date_to);
        if ($request->filled('lob')) $query->where('partner_lob', $request->lob);
        if ($request->filled('status')) $query->where('status', $request->status);

        $assignments = $query->latest('assigned_at')->get();
        
        $corteSummary = [
            'iva' => $assignments->sum('iva_amount'),
            'partners' => $assignments->groupBy('partner_lob')
                ->map(fn($group) => $group->sum('base_price'))
                ->sortDesc()
        ];

        $dateRange = '';
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateRange = 'Del ' . \Carbon\Carbon::parse($request->date_from)->format('d/m/Y') . 
                        ' al ' . \Carbon\Carbon::parse($request->date_to)->format('d/m/Y');
        } elseif ($request->filled('date_from')) {
            $dateRange = 'Desde ' . \Carbon\Carbon::parse($request->date_from)->format('d/m/Y');
        } elseif ($request->filled('date_to')) {
            $dateRange = 'Hasta ' . \Carbon\Carbon::parse($request->date_to)->format('d/m/Y');
        }

        return view('admin.assignments.pdf', compact('assignments', 'corteSummary', 'dateRange'));
    }

    public function destroy(ProductAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Asignación eliminada correctamente.');
    }
}
