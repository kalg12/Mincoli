<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAssignment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAssignmentController extends Controller
{
    public function index()
    {
        $assignments = ProductAssignment::with(['user', 'product.variants'])->latest()->paginate(15);
        return view('admin.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $users = User::where('role', 'employee')->orWhere('role', 'admin')->get();
        $products = Product::where('is_active', true)->with('variants')->get();
        return view('admin.assignments.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        // Logic to handle variants if any
        $product = Product::find($request->product_id);
        $variantId = $request->variant_id; // If passed

        ProductAssignment::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'variant_id' => $variantId,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->quantity * $request->unit_price,
            'amount_collected' => 0,
            'status' => 'assigned',
        ]);

        return redirect()->route('dashboard.assignments.index')->with('success', 'Asignación creada correctamente.');
    }
}
