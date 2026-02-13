<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorizedPhone;
use App\Models\Customer;
use Illuminate\Http\Request;

class AuthorizedPhoneController extends Controller
{
    public function index(Request $request)
    {
        $phones = AuthorizedPhone::query()
            ->when($request->filled('q'), fn ($q) => $q->where('phone', 'like', '%' . preg_replace('/\D/', '', $request->q) . '%'))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $authorizedPhones = AuthorizedPhone::pluck('id', 'phone')->toArray();

        $customersWithPhone = Customer::query()
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('name')
            ->get()
            ->map(function (Customer $customer) use ($authorizedPhones) {
                $normalized = AuthorizedPhone::normalizePhone($customer->phone);
                $alreadyAuthorized = isset($authorizedPhones[$normalized]);
                return (object)[
                    'customer' => $customer,
                    'normalized_phone' => $normalized,
                    'already_authorized' => $alreadyAuthorized,
                ];
            })
            ->filter(fn ($row) => strlen($row->normalized_phone) >= 10);

        return view('admin.exclusive-landing.phones.index', compact('phones', 'customersWithPhone'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:20'],
        ], [], ['phone' => 'número telefónico']);

        $normalized = AuthorizedPhone::normalizePhone($request->phone);
        if (strlen($normalized) < 10) {
            return redirect()->back()->withInput()->with('error', 'El número debe incluir lada (ej. 55 1234 5678).');
        }

        $exists = AuthorizedPhone::where('phone', $normalized)->first();
        if ($exists) {
            $exists->update(['is_active' => true]);
            return redirect()->back()->with('success', 'Número reactivado.');
        }

        AuthorizedPhone::create([
            'phone' => $normalized,
            'is_active' => true,
            'registered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Número autorizado agregado.');
    }

    public function destroy(AuthorizedPhone $authorized_phone)
    {
        $authorized_phone->delete();
        return redirect()->back()->with('success', 'Número eliminado.');
    }

    public function toggleActive(AuthorizedPhone $authorized_phone)
    {
        $authorized_phone->update(['is_active' => !$authorized_phone->is_active]);
        return redirect()->back()->with('success', $authorized_phone->is_active ? 'Número activado.' : 'Número desactivado.');
    }

    /**
     * Add a customer's phone to authorized list.
     */
    public function addFromCustomer(Customer $customer)
    {
        if (empty(trim((string) $customer->phone))) {
            return redirect()->back()->with('error', 'El cliente no tiene número registrado.');
        }

        $normalized = AuthorizedPhone::normalizePhone($customer->phone);
        if (strlen($normalized) < 10) {
            return redirect()->back()->with('error', 'El número del cliente no es válido (incluir lada).');
        }

        $exists = AuthorizedPhone::where('phone', $normalized)->first();
        if ($exists) {
            $exists->update(['is_active' => true]);
            return redirect()->back()->with('success', 'Número de ' . $customer->name . ' ya estaba en la lista; reactivado.');
        }

        AuthorizedPhone::create([
            'phone' => $normalized,
            'is_active' => true,
            'registered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Número de ' . $customer->name . ' agregado a autorizados.');
    }

    /**
     * Add all customers with valid phone to authorized list.
     */
    public function addAllFromCustomers()
    {
        $customers = Customer::query()
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();

        $added = 0;
        $reactivated = 0;

        foreach ($customers as $customer) {
            $normalized = AuthorizedPhone::normalizePhone($customer->phone);
            if (strlen($normalized) < 10) {
                continue;
            }

            $exists = AuthorizedPhone::where('phone', $normalized)->first();
            if ($exists) {
                if (!$exists->is_active) {
                    $exists->update(['is_active' => true]);
                    $reactivated++;
                }
            } else {
                AuthorizedPhone::create([
                    'phone' => $normalized,
                    'is_active' => true,
                    'registered_at' => now(),
                ]);
                $added++;
            }
        }

        $message = $added > 0 || $reactivated > 0
            ? "Listo: {$added} número(s) agregado(s), {$reactivated} reactivado(s)."
            : 'Todos los clientes con teléfono ya estaban autorizados.';

        return redirect()->back()->with('success', $message);
    }
}
