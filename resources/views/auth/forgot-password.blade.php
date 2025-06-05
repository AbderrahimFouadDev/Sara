<x-guest-layout>
      <!-- FIRST THING in the slot: -->
      <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/login.js') }}" defer></script>

    <div class="login-container">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Vous avez oublié votre mot de passe ? Pas de souci. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation.') }}
    </div>

    <!-- Statut de la session -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="reset-container">
        @csrf

        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="reset-btn">
                {{ __('Envoyer le lien de réinitialisation') }}
            </button>
        </div>
    </form>
</x-guest-layout>
