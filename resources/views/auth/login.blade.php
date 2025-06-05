<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" type="text/css">
    <script src="{{ asset('js/login.js') }}" defer></script>

    <div class="login-page">
        <!-- Left Panel -->
        <div class="brand-panel">
            <div class="wave-decoration">
                <div class="wave"></div>
                <div class="dots"></div>
                <div class="circle"></div>
                <div class="plus"></div>
            </div>
            <div class="brand-content">
                <h1>Bon retour!</h1>
                <p class="welcome-text">
                    Vous pouvez vous connecter pour accéder<br>
                    à votre compte existant.
                </p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="login-panel">
            <div class="login-content">
                <h2>Connexion</h2>

                <form method="POST" action="{{ route('login') }}" class="login-form">
    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <input id="email" 
                               class="form-input" 
                               type="email" 
                               name="email" 
                               :value="old('email')" 
                               placeholder="email d'utilisateur"
                               required 
                               autofocus />
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <input id="password" 
                               class="form-input" 
                        type="password"
                        name="password"
                               placeholder="Mot de passe"
                               required />
                        <x-input-error :messages="$errors->get('password')" class="error-message" />
    </div>

                    <div class="form-options">
                        <!-- Remember Me -->
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Se souvenir de moi</span>
        </label>

        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Mot de passe oublié?
            </a>
        @endif
                    </div>

                    <button type="submit" class="login-button">
            Connexion
                    </button>

                    <p class="signup-link">
                        Nouveau? <a href="{{ route('register') }}">Créer un compte</a>
                    </p>
                </form>
            </div>
    </div>
    </div>
</x-guest-layout>
