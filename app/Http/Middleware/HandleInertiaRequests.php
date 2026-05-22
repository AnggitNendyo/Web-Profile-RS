<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings_array', 3600, function () {
            $setting = \App\Models\SiteSetting::first();
            return $setting ? $setting->toArray() : [];
        });

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'global' => [
                'hospital_name' => $settings['hospital_name'] ?? config('app.name'),
                'hospital_description' => $settings['hospital_description'] ?? null,
                'contact' => [
                    'phone' => $settings['contact_phone'] ?? null,
                    'email' => $settings['contact_email'] ?? null,
                    'address' => $settings['contact_address'] ?? null,
                    'emergency' => $settings['contact_emergency'] ?? null,
                ],
                'social' => [
                    'facebook' => $settings['social_facebook'] ?? null,
                    'instagram' => $settings['social_instagram'] ?? null,
                    'youtube' => $settings['social_youtube'] ?? null,
                ]
            ]
        ];
    }
}
