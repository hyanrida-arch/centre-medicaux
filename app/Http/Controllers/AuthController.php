<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()  { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return auth()->user()->isDoctor()
                ? redirect()->route('doctor.dashboard')
                : redirect()->route('doctors.index');
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'required|in:patient,doctor',
            'phone'      => 'nullable|string|max:20',
        ];

        if ($request->role === 'doctor') {
            $rules['specialization']   = 'required|string|max:150';
            $rules['consultation_fee'] = 'required|numeric|min:0';
            $rules['biography']        = 'nullable|string|max:1000';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'phone'      => $validated['phone'] ?? null,
        ]);

        if ($user->isDoctor()) {
            Doctor::create([
                'user_id'          => $user->id,
                'specialization'   => $validated['specialization'],
                'consultation_fee' => $validated['consultation_fee'],
                'biography'        => $validated['biography'] ?? null,
            ]);
        }

        Auth::login($user);

        return $user->isDoctor()
            ? redirect()->route('doctor.dashboard')
            : redirect()->route('doctors.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
