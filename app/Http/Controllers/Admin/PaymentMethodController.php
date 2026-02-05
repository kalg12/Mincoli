<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'supports_card_number' => 'boolean',
            'card_number' => 'nullable|string|size:16',
            'card_type' => 'nullable|string|in:credit,debit',
            'card_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'instructions' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'code', 'description', 'instructions', 'card_number', 'card_type', 'card_holder_name', 'bank_name']);
        $data['is_active'] = $request->has('is_active');
        $data['supports_card_number'] = $request->has('supports_card_number');

        if ($request->has('settings')) {
            $data['settings'] = $request->input('settings');
        }

        PaymentMethod::create($data);

        return redirect()->route('dashboard.payment-methods.index')
            ->with('success', 'Método de pago creado correctamente.');
    }

    public function edit($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.payment-methods.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'supports_card_number' => 'boolean',
            'card_number' => 'nullable|string|size:16',
            'card_type' => 'nullable|string|in:credit,debit',
            'card_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'instructions' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'description', 'instructions', 'card_number', 'card_type', 'card_holder_name', 'bank_name']);
        $data['is_active'] = $request->has('is_active');
        $data['supports_card_number'] = $request->has('supports_card_number');

        if ($request->has('settings')) {
            $data['settings'] = $request->input('settings');
        }

        $method->update($data);

        return redirect()->route('dashboard.payment-methods.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $method = PaymentMethod::findOrFail($id);

        // Verificar si hay pagos asociados
        if ($method->payments()->count() > 0) {
            return redirect()->route('dashboard.payment-methods.index')
                ->with('error', 'No se puede eliminar este método de pago porque tiene pagos asociados.');
        }

        $method->delete();

        return redirect()->route('dashboard.payment-methods.index')
            ->with('success', 'Método de pago eliminado correctamente.');
    }
}
