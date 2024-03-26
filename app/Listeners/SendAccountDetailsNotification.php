<?php

namespace App\Listeners;

use App\Notifications\AccountInformation;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAccountDetailsNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        // Récupérer le numéro de compte de l'utilisateur
        $accountNumber = $user->accountNumbers()->latest()->first()->account_number;
        // Récupérer le package associé à l'utilisateur (s'il en a un)
        $package = null;
        if ($user->subscriptionPackage) {
            $package = $user->subscriptionPackage->package;
        }
        // Envoyer le mail avec les détails du compte
        $user->notify(new AccountInformation($user, $accountNumber, $package));
    }
}
