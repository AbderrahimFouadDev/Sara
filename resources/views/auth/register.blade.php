<x-guest-layout>
    <!-- Error Modal -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<div id="error-modal" class="error-modal hidden">
  <div class="error-modal__content">
    <p id="error-message" class="error-modal__message"></p>
    <button id="modal-close" class="error-modal__button">OK</button>
  </div>
</div>

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('js/register.js') }}" defer></script>

    <div class="registration-container">
        <!-- Left Section -->
        <div class="left-section">
            <!-- Welcome Text instead of Logo -->
            <div class="welcome-container mb-8">
                <h1 class="welcome-text">Bienvenue à notre site</h1>
                <div class="welcome-decoration"></div>
            </div>
            <style>
                .welcome-container {
                    text-align: center;
                    padding: 1.5rem 0;
                    position: relative;
                }
                .welcome-text {
                    font-size: 2.2rem;
                    font-weight: 700;
                    color: white;
                    margin-bottom: 0.5rem;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
                    animation: fadeIn 1s ease-in-out;
                }
                .welcome-decoration {
                    height: 4px;
                    width: 80px;
                    background: linear-gradient(90deg, #3b82f6, #60a5fa);
                    margin: 0 auto;
                    border-radius: 2px;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
            <!-- Steps -->
            <div class="step" id="step1">
                <div class="circle">1</div>
                <h3>Paramètres du compte</h3>
                <p>Configurez les détails de votre compte</p>
            </div>
            <div class="step" id="step2">
                <div class="circle">2</div>
                <h3>Vérification e-mail</h3>
                <p>Confirmation de votre adresse e-mail</p>
            </div>
            <div class="step" id="step3">
                <div class="circle">3</div>
                <h3>Paramètres de l'entreprise</h3>
                <p>Informations sur votre entreprise</p>
            </div>
            <div class="step" id="step4">
                <div class="circle">4</div>
                <h3>Félicitations !</h3>
                <p>Accéder à votre compte</p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <form method="POST" action="{{ route('register.finish.post') }}">
                @csrf

                <!-- Step 1 -->
                <div class="form-step" id="form-step-1">
                    <h2>Créer Gratuitement votre compte</h2>
                    
                    <p>Vous avez déjà un compte ? <a href="{{ route('login') }}">Connectez-vous !</a></p>

                    <label>Nom *</label>
                    <input type="text" name="nom" required>

                    <label>Prénom *</label>
                    <input type="text" name="prenom" required>

                    <label>Téléphone *</label>
                    <input type="tel" name="telephone" required>

                    <label>E-mail *</label>
                    <input type="email" name="email" required>
                    <!-- New Password fields -->
<label>Mot de passe *</label>
<input type="password" name="password" required minlength="8">

<label>Confirmer mot de passe *</label>
<input type="password" name="password_confirmation" required minlength="8">


                    <button type="button" class="next-btn" data-next="2">Valider et continuer</button>
                </div>

                <!-- Step 2 -->
                <div class="form-step hidden" id="form-step-2">
                    <h2>Vérification Email</h2>
                    <p>Un code a été envoyé à votre adresse email.</p>
                    <label>Code *</label>
                    <input type="text" name="code_verification" required>

                    <button type="button" class="next-btn" data-next="3">Valider et continuer</button>
                </div>

                <!-- Step 3 -->
                <div class="form-step hidden" id="form-step-3">
                    <h2>Paramètres de l'entreprise</h2>
                    <label>Nom de l'entreprise</label>
                    <input type="text" name="entreprise">

                    <label>Adresse</label>
                    <input type="text" name="adresse_entreprise">

                    <label>Secteur</label>
                    <input type="text" name="secteur">

                    <!-- Still using Valider et continuer -->
                    <button type="button" class="next-btn" data-next="4">Valider et continuer</button>
                </div>

                <!-- Step 4 -->
<div class="form-step hidden" id="form-step-4">
    <h2>Félicitations !</h2>
    <p>Votre compte a été créé avec succès.</p>
    <button type="submit" class="finish-btn">Accéder à votre compte</button>
</div>

            </form>
        </div>
    </div>
</x-guest-layout>
