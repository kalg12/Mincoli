<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerNote;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->withSum('orders as total_spent', 'total')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Customer::count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:20|unique:customers',            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',        ], [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya existe',
            'phone.required' => 'El teléfono es requerido',
            'phone.unique' => 'Este teléfono ya existe',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente creado correctamente');
    }

    public function show($id)
    {
        $customer = Customer::withCount('orders')
            ->withCount(['orders as pending_orders' => fn ($q) => $q->where('status', 'pending')])
            ->withSum('orders as total_spent', 'total')
            ->with([
                'orders' => fn ($q) => $q->latest()->take(20),
                'notes.user',
                'addresses',
            ])->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'required|string|max:20|unique:customers,phone,' . $id,
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya existe',
            'phone.required' => 'El teléfono es requerido',
            'phone.unique' => 'Este teléfono ya existe',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Cliente actualizado correctamente');
    }

    public function addNote(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        CustomerNote::create([
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Nota agregada');
    }

    public function storeAddress(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'ext_number' => 'nullable|string|max:20',
            'int_number' => 'nullable|string|max:20',
            'colony' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'references' => 'nullable|string|max:500',
            'is_default' => 'nullable',
        ]);

        $validated['customer_id'] = $customer->id;

        if ($request->has('is_default') || $customer->addresses()->count() === 0) {
            $customer->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        } else {
            $validated['is_default'] = false;
        }

        CustomerAddress::create($validated);

        return back()->with('success', 'Dirección agregada correctamente');
    }

    public function updateAddress(Request $request, $customerId, $addressId)
    {
        $address = CustomerAddress::where('customer_id', $customerId)->findOrFail($addressId);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'ext_number' => 'nullable|string|max:20',
            'int_number' => 'nullable|string|max:20',
            'colony' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'references' => 'nullable|string|max:500',
            'is_default' => 'nullable',
        ]);

        if ($request->has('is_default')) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
            $validated['is_default'] = true;
        } else {
            $validated['is_default'] = false;
        }

        $address->update($validated);

        return back()->with('success', 'Dirección actualizada correctamente');
    }

    public function destroyAddress($customerId, $addressId)
    {
        $address = CustomerAddress::where('customer_id', $customerId)->findOrFail($addressId);
        
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $firstAddress = CustomerAddress::where('customer_id', $customerId)->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Dirección eliminada correctamente');
    }

    public function setDefaultAddress($customerId, $addressId)
    {
        $address = CustomerAddress::where('customer_id', $customerId)->findOrFail($addressId);
        $address->setAsDefault();

        return back()->with('success', 'Dirección predeterminada actualizada');
    }
}
