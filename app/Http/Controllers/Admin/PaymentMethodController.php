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
            'settings' => 'nullable|array',
            'instructions' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'description', 'instructions']);
        $data['is_active'] = $request->has('is_active');
        
        if ($request->has('settings')) {
            $data['settings'] = $request->input('settings');
        }

        $method->update($data);

        return redirect()->route('dashboard.payment-methods.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }
}
