<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'show_iva' => SiteSetting::get('store', 'show_iva', true)
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'show_iva' => 'required|boolean'
        ]);

        SiteSetting::set('store', 'show_iva', $request->show_iva);

        return back()->with('success', 'Configuración actualizada correctamente');
    }
}
