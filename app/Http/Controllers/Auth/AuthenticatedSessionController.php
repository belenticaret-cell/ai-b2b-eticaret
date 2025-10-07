<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Bayi;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        // Rol tabanlı yönlendirme
        if (($user->rol ?? null) === 'admin') {
            return redirect()->intended(route('admin.panel', absolute: false));
        }
        if (($user->rol ?? null) === 'bayi') {
            return redirect()->intended(route('bayi.panel', absolute: false));
        }
        if (($user->rol ?? null) === 'musteri') {
            // Aktif bayi varsa bayi vitrini sayfasına yönlendir
            $aktifBayiId = $request->session()->get('aktif_bayi_id');
            if ($aktifBayiId && ($bayi = Bayi::find($aktifBayiId))) {
                return redirect()->intended(route('vitrin.bayi', ['bayi' => $bayi->id], absolute: false));
            }
            // Aksi halde public mağaza
            return redirect()->intended(route('vitrin.magaza', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
