<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\user;

class AuthController extends Controller
{

    public function signup(Request $request)
    {
        if ($request->isMethod('post')) {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'regex:/[A-Z]/',      // at least one uppercase
                    'regex:/[a-z]/',      // at least one lowercase
                    'regex:/[0-9]/',      // at least one number
                ],
            ], [
                'password.regex' => 'Password must contain uppercase, lowercase, and number.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.confirmed' => 'Passwords do not match.',
            ]);

            if (user::where('email', $request->email)->exists()) {
                return back()
                    ->with('error', 'Email already registered! Please login')
                    ->withInput();
            }

            $user = User::create($validated);

            Auth::login($user);
            $request->session()->regenerate();

            if (!$user) {
                return back()->with('fails', 'Saving user failed');
            }

            return redirect()->route('login')->with('success', 'Registered Successfully');
        }

        return view('auth.signup');
    }

    /* =========================
       LOGIN
    ========================= */
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {

            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            
            if (Auth::attempt($credentials)) {

                $request->session()->regenerate();

                // Get the authenticated user and store permissions in session
                $user = Auth::user();
                $permissionNames = $user->getPermissionNames();
                // dd($permissionNames);    
                $request->session()->put('permissions', $permissionNames);

                return redirect()->route('Dashboard')->with('success', 'Logged in Successfully');
            }

            return back()->with('error', 'Invalid Username and Password');
        }

        return view('auth.login');
    }

    /* =========================
       LOGOUT
    ========================= */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
