<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorizedPhone;
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

        return view('admin.exclusive-landing.phones.index', compact('phones'));
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
}
