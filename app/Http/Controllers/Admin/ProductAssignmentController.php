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
        $query = ProductAssignment::with(['user', 'product.variants'])->latest();

        // Optional filtering by LOB or Date if needed in the future
        if ($request->filled('lob')) {
            $query->where('partner_lob', $request->lob);
        }

        $assignments = $query->paginate(30);

        // Calculate Corte Summary
        $allAssignments = $query->get(); // For summary we need all in current view/filter
        $corteSummary = [
            'iva' => $allAssignments->sum('iva_amount'),
            'partners' => $allAssignments->groupBy('partner_lob')
                ->map(fn($group) => $group->sum('base_price'))
        ];

        return view('admin.assignments.index', compact('assignments', 'corteSummary'));
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
}
