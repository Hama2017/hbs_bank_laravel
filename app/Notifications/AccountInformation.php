<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountInformation extends Notification
{
    use Queueable;

    protected $user;
    protected $accountNumber;
    protected $package;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\User  $user
     * @param  string  $accountNumber
     * @param  \App\Models\Package  $package
     * @return void
     */
    public function __construct($user, $accountNumber, $package)
    {
        $this->user = $user;
        $this->accountNumber = $accountNumber;
        $this->package = $package;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Détails de votre compte chez HBS BANK')
        ->line('Bonjour ' . $this->user->first_name . ',')
        ->line('Félicitations! Votre compte chez HBS BANK a été créé avec succès. Voici les détails de votre compte :')
        ->line('Numéro de compte : ' . $this->accountNumber)
        ->line('Type de compte : ' . $this->user->accountType->name)
        ->line('Email : ' . $this->user->email)
        ->line('Nom complet : ' . $this->user->first_name . ' ' . $this->user->last_name)
        ->line('Adresse : ' . $this->user->address)
        ->line('Numéro de téléphone : ' . $this->user->phone_number)
        ->line('CNI : ' . $this->user->cni)
        ->line('Pack choisi : ' . $this->package->name);

    if ($this->accountType === 'courant') {
        $mailMessage->line('Limite Transfere : ' . $this->package->limit_amount.' F CFA / MOIS')
            ->line('Agios : ' . $this->package->agios_fees.' F CFA / MOIS');
    }

    $mailMessage->line('Merci d\'avoir choisi HBS BANK. Bienvenue parmi nous !');

    }
}
