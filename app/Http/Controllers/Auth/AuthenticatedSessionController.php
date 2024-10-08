<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    // Authenticate the user
    $request->authenticate();

    // Regenerate session to prevent fixation attacks
    $request->session()->regenerate();

    // Get the authenticated user
    $user = Auth::user();

    // Determine redirection based on user role
    if ($user->hasRole('admin')) {  // Adjust this line if needed
        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    } elseif ($user->hasRole('super-admin')) { // Optional: if you have super-admin role
        return redirect()->intended(RouteServiceProvider::SUPER_ADMIN_HOME);
    } else {
        return redirect()->intended(RouteServiceProvider::HOME);
    }
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
