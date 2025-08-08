<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration attempt.
     */
    public function store(UserRegistrationRequest $request): RedirectResponse
    {
        // Create the user
        $user = User::create([
            'name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'password' => Hash::make($request->validated()['password']),
            'role' => 'colaborador', // Default role
        ]);

        // Log the user in automatically
        Auth::login($user);

        // Redirect to dashboard with success message
        return redirect('/dashboard')->with('success', 'Conta criada com sucesso! Bem-vindo ao TaskFlow.');
    }
}