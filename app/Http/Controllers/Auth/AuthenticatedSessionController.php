<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }
    public function createToken(){
        return view('auth.token-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::HOME);
        $request->authenticate();

        $request->session()->regenerate();

        $url = "";
        if($request->user()->role === "admin"){
            $url = "petugas";
        }elseif($request->user()->role === "petugas"){
            $url = "pemeriksaan";
        }else{
            $url = "dashboard";
        }

        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function loginWithToken(Request $request): RedirectResponse
{
    // Validasi input
    $request->validate([
        'token' => 'required|string',
    ]);

    // Cari user berdasarkan token
    $user = \App\Models\User::where('unique_token', $request->token)->first();

    // Jika user tidak ditemukan
    if (!$user) {
        return back()->with('error', 'Invalid token. Please try again.');
    }

    // Login pengguna
    Auth::login($user);

    // Redirect berdasarkan peran
    $url = match ($user->role) {
        'admin' => 'petugas',
        'petugas' => 'pemeriksaan',
        default => 'dashboard',
    };

    return redirect()->intended($url)->with('success', 'Login successful!');
}

     public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
