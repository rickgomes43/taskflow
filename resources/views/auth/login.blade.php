@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-foreground">TaskFlow</h1>
            <p class="mt-2 text-sm text-muted-foreground">
                Faça login em sua conta
            </p>
        </div>

        <!-- Login Form Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h2 class="text-2xl font-semibold leading-none tracking-tight">
                    Login
                </h2>
                <p class="text-sm text-muted-foreground">
                    Digite seus dados para acessar sua conta
                </p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="p-6 pt-0 space-y-4" id="loginForm">
                @csrf

                <!-- Success/Status Messages -->
                @if (session('status'))
                    <div class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required
                        value="{{ old('email') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="seu.email@exemplo.com"
                        aria-describedby="email-error"
                    >
                    @error('email')
                        <p id="email-error" class="text-sm text-destructive" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Senha
                    </label>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Digite sua senha"
                        aria-describedby="password-error"
                    >
                    @error('password')
                        <p id="password-error" class="text-sm text-destructive" role="alert">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center space-x-2">
                    <input 
                        id="remember" 
                        name="remember" 
                        type="checkbox" 
                        value="1"
                        class="h-4 w-4 rounded border border-input text-primary focus:ring-ring focus:ring-2 focus:ring-offset-2"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Lembrar-me
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full"
                    id="submitButton"
                >
                    <span id="buttonText">Entrar</span>
                    <svg id="loadingSpinner" class="animate-spin -mr-1 ml-3 h-5 w-5 text-primary-foreground hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <!-- Links -->
                <div class="text-center text-sm">
                    <a href="#" class="text-primary underline-offset-4 hover:underline">
                        Esqueceu sua senha?
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="flex items-center p-6 pt-0">
                <p class="text-sm text-muted-foreground text-center w-full">
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" class="text-primary underline-offset-4 hover:underline">
                        Cadastre-se
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');

    form.addEventListener('submit', function() {
        // Show loading state
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50');
        buttonText.textContent = 'Entrando...';
        loadingSpinner.classList.remove('hidden');
    });

    // Reset loading state on page load (in case of validation errors)
    submitButton.disabled = false;
    submitButton.classList.remove('opacity-50');
    buttonText.textContent = 'Entrar';
    loadingSpinner.classList.add('hidden');
});
</script>
@endpush
@endsection