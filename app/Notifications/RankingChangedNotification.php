<?php

namespace App\Notifications;

use App\Events\RankingChanged;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RankingChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public RankingChanged $event
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $keyword = $this->event->keyword;
        $change = $this->event->oldPosition - $this->event->newPosition;
        $improved = $change > 0;

        $message = (new MailMessage)
            ->subject('Changement de Position - ' . $keyword->keyword)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons détecté un changement significatif dans le classement de votre mot-clé.');

        if ($improved) {
            $message->success()
                ->line("🎉 Bonne nouvelle ! Le mot-clé **{$keyword->keyword}** a progressé de **{$change} position(s)** !")
                ->line("Nouvelle position : **#{$this->event->newPosition}**");
        } else {
            $changeAbs = abs($change);
            $message->warning()
                ->line("⚠️ Le mot-clé **{$keyword->keyword}** a reculé de **{$changeAbs} position(s)**.")
                ->line("Nouvelle position : **#{$this->event->newPosition}**");
        }

        return $message
            ->line("Projet : {$keyword->project->name}")
            ->action('Voir le Projet', url('/projects/' . $keyword->project->id))
            ->line('Continuez à optimiser votre contenu pour améliorer vos positions !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $change = $this->event->oldPosition - $this->event->newPosition;

        return [
            'type' => 'ranking_change',
            'keyword_id' => $this->event->keyword->id,
            'keyword' => $this->event->keyword->keyword,
            'project_id' => $this->event->keyword->project_id,
            'project_name' => $this->event->keyword->project->name,
            'old_position' => $this->event->oldPosition,
            'new_position' => $this->event->newPosition,
            'change' => $change,
            'improved' => $change > 0,
        ];
    }
}
