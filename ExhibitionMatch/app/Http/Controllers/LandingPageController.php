<?php

namespace App\Http\Controllers;

use App\Models\WebSetting;

class LandingPageController extends Controller
{
    public function index()
    {
        $settings = WebSetting::query()->orderByDesc('Web_ID')->first();

        $landingImageUrl = null;
        if ($settings?->landing_image_path) {
            // Expect a path relative to the "public" disk, e.g. "websettings/landing.jpg"
            // Use asset() so host/port always matches the current request (avoids APP_URL mismatch).
            $landingImageUrl = asset('storage/' . ltrim($settings->landing_image_path, '/'));
        }

        return view('visitor.landing', [
            'settings' => $settings,
            'landingImageUrl' => $landingImageUrl,
        ]);
    }
}


