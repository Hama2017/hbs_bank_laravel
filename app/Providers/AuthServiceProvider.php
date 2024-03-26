<?php

namespace App\Providers;

use App\Models\User;
use App\Models\AccountNumber;
use App\Models\AccountType;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage; // Ajout de l'importation de la classe MailMessage
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            // Récupérer l'utilisateur
            $user = User::findOrFail($notifiable->getKey());
            // Récupérer le numéro de compte de l'utilisateur
            $accountNumber = AccountNumber::where('id_user', $user->id)->latest()->first();
            // Récupérer le package associé à l'utilisateur
            $accountType = $accountNumber->accountType->name ?? 'Inconnu'; // Accéder au nom du type de compte
            return (new MailMessage)
                ->subject('Bienvenue chez HBS BANK - Vérifiez votre adresse e-mail')
                ->line('Bonjour, M.'. $user->first_name . ' ' . $user->last_name)
                ->line('Merci de vous être inscrit chez HBS BANK. Avant de continuer, vous devez vérifier votre adresse e-mail pour activer votre compte en cliquant sur le bouton ci-dessous :')
                ->action('Vérifier mon adresse e-mail', $url)
                ->line('Agence : MARS')
                ->line('Gestionnaire de compte : Madame Aicha Diop')
                ->line('Adresse email : aicha.dio@hbs.bank')
                ->line('Numéro de compte : ' . $accountNumber->account_number)
                ->line('Type de compte : ' . $accountType)
                ->line('Email : ' . $user->email)
                ->line('Nom complet : ' . $user->first_name . ' ' . $user->last_name)
                ->line('Adresse : ' . $user->address)
                ->line('Numéro de téléphone : ' . $user->phone_number)
                ->line('CNI : ' . $user->cni)
                ->line('Cordialement,')
                ->line('HBS BANK');
        });
    }

}
