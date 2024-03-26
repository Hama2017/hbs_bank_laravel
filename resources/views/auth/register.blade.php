<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Première étape du formulaire -->
            <div id="step1">
                <div class="mt-4">
                    <label for="first_name" class="block font-medium text-sm text-gray-700">{{ __('First Name') }}</label>
                    <input id="first_name" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" class="block w-full px-3 py-2 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div class="mt-4">
                    <label for="last_name" class="block font-medium text-sm text-gray-700">{{ __('Last Name') }}</label>
                    <input id="last_name" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" class="block w-full px-3 py-2 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div class="mt-4">
                    <label for="phone_number" class="block font-medium text-sm text-gray-700">{{ __('Phone Number') }}</label>
                    <input id="phone_number" type="text" name="phone_number" :value="old('phone_number')" required autofocus autocomplete="phone_number" class="block w-full px-3 py-2 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div class="mt-4">
                    <label for="address" class="block font-medium text-sm text-gray-700">{{ __('Address') }}</label>
                    <input id="address" type="text" name="address" :value="old('address')" required autofocus autocomplete="address" class="block w-full px-3 py-2 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div class="mt-4">
                    <label for="cni" class="block font-medium text-sm text-gray-700">{{ __('CNI') }}</label>
                    <input id="cni" type="text" name="cni" :value="old('cni')" required autofocus autocomplete="cni" class="block w-full px-3 py-2 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500">
                </div>

                <button id="nextStep">Suivant</button>
            </div>

          <!-- Deuxième étape du formulaire (chargée dynamiquement via AJAX) -->
<div id="step2" style="display: none;">
    <div id="accountTypeSelection" class="mt-4">
        <label for="account_type" class="block font-medium text-sm text-gray-700">Type de compte</label>
        <select id="account_type" class="block w-full mt-1" name="id_account_type">
            <option value="" required>Sélectionner le type de compte</option>
        </select>
    </div>

    <div id="packSelection" class="mt-4" style="display: none;">
        <label for="pack" class="block font-medium text-sm text-gray-700">Choisissez votre pack</label>
        <select id="pack" class="block w-full mt-1" name="id_package">
            <option value="">Sélectionner le pack</option>
        </select>
    </div>



    <div class="mt-4">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
    </div>

    <div class="mt-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
    </div>

    <div class="mt-4">
        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
    </div>

    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
            <x-label for="terms">
                <div class="flex items-center">
                    <x-checkbox name="terms" id="terms" required />

                    <div class="ms-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-label>
        </div>
    @endif

    <x-button class="ms-4">
        {{ __('Register') }}
    </x-button>
    <button id="register">Terminer</button>
</div>

        </form>
    </x-authentication-card>
</x-guest-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  $(document).ready(function() {
    // Lorsque le document est prêt, exécuter les actions suivantes :

    // Gérer le changement du type de compte

        var accountType = $(this).val();
        // Faire une requête AJAX pour récupérer les types de compte depuis le serveur
        $.ajax({
            url: '/account-types',
            method: 'GET',
            success: function(response) {
                // Mettre à jour le select des types de compte avec les données reçues
                var accountTypeSelect = $('#account_type');
                $.each(response.accountTypes, function(index, accountType) {
                    accountTypeSelect.append('<option value="' + accountType.id + '">' + accountType.name + '</option>');
                });
            }
        });




    // Gérer le clic sur le bouton "Suivant"
    $('#nextStep').click(function(e) {
        e.preventDefault();
        // Masquer la première étape et afficher la deuxième étape
        $('#step1').hide();
        $('#step2').show();
    });

    // Gérer le clic sur le bouton "Register"
    $('#register').click(function(e) {
        e.preventDefault();
        // Récupérer les données du formulaire
        var selectedPack = $('#pack').val(); // Le pack sélectionné
        var formData = $('#registerForm').serialize();

        // Faire une requête AJAX pour soumettre les données au contrôleur Laravel
        $.ajax({
            url: '/register',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(response) {

            }
        });
    });

    // Gérer le changemcent du type de compte pour charger les packs correspondants
    $('#account_type').change(function() {
        var accountType = $(this).val();
        if (accountType == 1) {
            $('#packSelection').show();
        } else {
            $('#packSelection').hide();
        }
        // Faire une requête AJAX pour récupérer les packs depuis le serveur
        $.ajax({
            url: '/packages',
            method: 'GET',
            data: {
                account_type: accountType
            },
            success: function(response) {
                // Mettre à jour le select des packs avec les données reçues
                var packSelect = $('#pack');
                packSelect.empty();
                $.each(response.packages, function(index, pack) {
                    packSelect.append('<option value="' + pack.id + '">' + pack.name + '</option>');
                });
            }
        });
    });
});



</script>
