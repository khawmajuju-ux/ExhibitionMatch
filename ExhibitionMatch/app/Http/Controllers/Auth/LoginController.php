<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Models\WebSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $settings = WebSetting::query()->orderByDesc('Web_ID')->first();

        $landingImageUrl = null;
        if ($settings?->landing_image_path) {
            // Expect a path relative to the "public" disk, e.g. "websettings/landing.jpg"
            // Use asset() so host/port always matches the current request (avoids APP_URL mismatch).
            $landingImageUrl = asset('storage/' . ltrim($settings->landing_image_path, '/'));
        }

        return view('auth.login', [
            'settings' => $settings,
            'landingImageUrl' => $landingImageUrl,
        ]);
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Find admin by username
        $admin = Admin::where('admin_username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->admin_password)) {
            Auth::guard('admin')->login($admin, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        throw ValidationException::withMessages([
            'username' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

