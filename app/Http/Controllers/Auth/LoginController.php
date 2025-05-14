<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Default fallback redirect (not used if `authenticated()` exists).
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect users based on role after login.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'staff') {
            return redirect('/staff/dashboard');
        }

        if ($user->role === 'customer') {
            return redirect('/customer/dashboard');
        }

        // fallback
        return redirect('/home');
    }
}
