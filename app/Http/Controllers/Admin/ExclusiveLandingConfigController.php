<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExclusiveLandingConfig;
use Illuminate\Http\Request;

class ExclusiveLandingConfigController extends Controller
{
    public function index()
    {
        $config = ExclusiveLandingConfig::first();
        if (!$config) {
            $config = new ExclusiveLandingConfig([
                'contact_phone' => '55 0000 0000',
                'restricted_message' => "¡Gracias por visitarnos! Este espacio es exclusivo para clientas especiales y tu número aún no está registrado en nuestra lista.\n\nSi deseas recibir más información o validar tu acceso, escríbenos al [55 0000 0000]. Estaremos encantadas de ayudarte.",
                'expired_message' => 'Esta campaña de contenido exclusivo ha finalizado. Te esperamos en la próxima.',
            ]);
        }
        return view('admin.exclusive-landing.config', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'contact_phone' => 'nullable|string|max:20',
            'restricted_message' => 'nullable|string|max:1000',
            'expired_message' => 'nullable|string|max:1000',
            'show_filter_category' => 'boolean',
            'show_filter_type' => 'boolean',
            'show_filter_price' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['show_filter_category'] = $request->boolean('show_filter_category');
        $validated['show_filter_type'] = $request->boolean('show_filter_type');
        $validated['show_filter_price'] = $request->boolean('show_filter_price');

        $config = ExclusiveLandingConfig::first();
        if (!$config) {
            $config = ExclusiveLandingConfig::create($validated);
        } else {
            $config->update($validated);
        }

        ExclusiveLandingConfig::clearCache();

        return redirect()->route('dashboard.exclusive-landing.config')
            ->with('success', 'Configuración de la landing exclusiva guardada.');
    }
}
