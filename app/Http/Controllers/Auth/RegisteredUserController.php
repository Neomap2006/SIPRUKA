<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle register baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nipd'     => ['nullable', 'string', 'max:50'],
            'prodi'    => ['nullable', 'string', 'max:100'],
            'no_hp'    => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
            'nipd'     => $request->nipd,
            'prodi'    => $request->prodi,
            'no_hp'    => $request->no_hp,
        ]);


        // Event registered
        event(new Registered($user));

        // Login otomatis
        Auth::login($user);

        // Redirect sesuai role
        return redirect()->route('dashboard');
    }
}
