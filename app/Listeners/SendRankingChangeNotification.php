<?php

namespace App\Listeners;

use App\Events\RankingChanged;
use App\Notifications\RankingChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRankingChangeNotification implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(RankingChanged $event): void
    {
        $keyword = $event->keyword;
        $user = $keyword->project->user;

        // Check if user wants to be notified
        if (!$user->notification_preferences['ranking_changes'] ?? false) {
            return;
        }

        // Only notify for significant changes (5+ positions)
        $change = abs($event->oldPosition - $event->newPosition);
        if ($change < 5) {
            return;
        }

        // Send notification
        $user->notify(new RankingChangedNotification($event));
    }

    /**
     * Handle a job failure.
     */
    public function failed(RankingChanged $event, \Throwable $exception): void
    {
        \Log::error('Failed to send ranking change notification', [
            'keyword_id' => $event->keyword->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
