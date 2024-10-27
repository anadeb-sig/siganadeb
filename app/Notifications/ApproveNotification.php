<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApproveNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $details;
    protected $ouvrages;

    public function __construct($details, $ouvrages)
    {
        $this->details = $details;
        $this->ouvrages = $ouvrages;
    }

    public function via($notifiable)
    {
        return ['mail']; // Ou 'database', 'broadcast', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Demande d\'approbation')
            ->line('Vous avez une nouvelle demande d\'approbation.')
            ->action('Voir la demande', url('demandes/detail/' . $this->details['id'] . '/'. $this->ouvrages['ouvrage_id'] ))
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
