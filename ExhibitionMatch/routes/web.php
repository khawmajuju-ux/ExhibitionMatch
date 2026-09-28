<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VisitorRegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProblemTagController;
use App\Http\Controllers\Admin\ExhibitorController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\MatchingController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\WebSettingController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/visitor/register', [VisitorRegistrationController::class, 'create'])->name('visitor.register');
Route::post('/visitor/register', [VisitorRegistrationController::class, 'store'])->name('visitor.register.store');
Route::get('/visitor/matching', [VisitorRegistrationController::class, 'matchingLatest'])->name('visitor.matching.latest');
Route::get('/visitor/matching/{visitor}', [VisitorRegistrationController::class, 'matching'])->name('visitor.matching');

// Common misspelling alias
Route::get('/visiter/register', [VisitorRegistrationController::class, 'create'])->name('visiter.register');
Route::post('/visiter/register', [VisitorRegistrationController::class, 'store'])->name('visiter.register.store');
Route::get('/visiter/matching', [VisitorRegistrationController::class, 'matchingLatest'])->name('visiter.matching.latest');
Route::get('/visiter/matching/{visitor}', [VisitorRegistrationController::class, 'matching'])->name('visiter.matching');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {
        Route::redirect('/', '/admin/dashboard')->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('export/excel', [ExportController::class, 'excel'])->name('export.excel');
        Route::get('locale/{locale}', function (\Illuminate\Http\Request $request, string $locale) {
            if (!in_array($locale, ['ja', 'en'], true)) {
                return back();
            }

            $request->session()->put('locale', $locale);

            $redirect = (string) $request->query('redirect', url()->previous());
            $base = url('/');
            if (!str_starts_with($redirect, $base)) {
                $redirect = $base;
            }

            return redirect($redirect);
        })->name('locale.set');

        Route::resource('problemtags', ProblemTagController::class)
            ->parameters(['problemtags' => 'problemTag'])
            ->except(['show']);

        Route::delete('exhibitors/bulk', [ExhibitorController::class, 'bulkDestroy'])->name('exhibitors.bulkDestroy');

        Route::resource('exhibitors', ExhibitorController::class)
            ->parameters(['exhibitors' => 'exhibitor'])
            ->except([]);

        Route::get('visitors', [VisitorController::class, 'index'])->name('visitors.index');
        Route::get('visitors/{visitor}', [VisitorController::class, 'show'])->name('visitors.show');
        Route::delete('visitors/bulk', [VisitorController::class, 'bulkDestroy'])->name('visitors.bulkDestroy');
        Route::delete('visitors/{visitor}', [VisitorController::class, 'destroy'])->name('visitors.destroy');

        Route::get('matching', [MatchingController::class, 'index'])->name('matching.index');
        Route::post('matching/export', [MatchingController::class, 'export'])->name('matching.export');

        Route::resource('websettings', WebSettingController::class)
            ->parameters(['websettings' => 'webSetting'])
            ->except(['show']);

        Route::get('websettings/{webSetting}/preview', [WebSettingController::class, 'preview'])
            ->name('websettings.preview');
    });
