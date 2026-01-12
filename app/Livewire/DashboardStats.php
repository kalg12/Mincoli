<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;

class DashboardStats extends Component
{
    public $salesToday = 0;
    public $activeOrdersCount = 0;
    public $lowStockVariantsCount = 0;
    public $customersCount = 0;
    public $latestOrders = [];
    public $activeBanners = [];
    public $featuredProducts = [];
    public $paymentMethods = [];

    public function mount(): void
    {
        $this->salesToday = Order::where('status', 'paid')->whereDate('placed_at', today())->sum('total');
        $this->activeOrdersCount = Order::whereIn('status', ['pending', 'paid', 'partially_paid', 'shipped'])->count();
        $this->lowStockVariantsCount = ProductVariant::where('stock', '<', 5)->count();
        $this->customersCount = Customer::count();
        $this->latestOrders = Order::with('customer')->where('status', '!=', 'draft')->latest()->take(5)->get();
        $this->activeBanners = Banner::where('is_active', true)->take(2)->get();
        $this->featuredProducts = Product::where('is_featured', true)->take(3)->get();
        $this->paymentMethods = PaymentMethod::all();
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}