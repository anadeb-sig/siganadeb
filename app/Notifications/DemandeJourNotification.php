<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DemandeJourNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($details, $contrats,$date_fin)
    {
        $this->details = $details;
        $this->contrats = $contrats;
        $this->date_fin = $date_fin;
    }

    public function via($notifiable)
    {
        return ['mail']; // Ou 'database', 'broadcast', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Demande d\'ajout de jour sur le contrat')
            ->line('Vous avez une nouvelle demande d\'approbation.')
            ->action('Voir la demande', url('demandejours/detail/' . $this->details['id']))
            ->line('Merci d\'utiliser notre application!');
    }

    // Si vous utilisez une notification en base de données
    public function toArray($notifiable)
    {
        return [
            'id' => $this->details['id'],
            'message' => 'Nouvelle demande d\'approbation.',
        ];
    }
}
