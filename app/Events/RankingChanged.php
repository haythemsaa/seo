<?php

namespace App\Events;

use App\Models\Keyword;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RankingChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Keyword $keyword,
        public int $oldPosition,
        public int $newPosition
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->keyword->project->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ranking.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $change = $this->oldPosition - $this->newPosition;

        return [
            'keyword_id' => $this->keyword->id,
            'keyword' => $this->keyword->keyword,
            'project_id' => $this->keyword->project_id,
            'project_name' => $this->keyword->project->name,
            'old_position' => $this->oldPosition,
            'new_position' => $this->newPosition,
            'change' => $change,
            'improved' => $change > 0,
            'message' => $this->getMessage($change),
        ];
    }

    /**
     * Get notification message based on change.
     */
    private function getMessage(int $change): string
    {
        $keyword = $this->keyword->keyword;

        if ($change > 0) {
            return "Le mot-clé '{$keyword}' a progressé de {$change} position(s) !";
        } elseif ($change < 0) {
            return "Le mot-clé '{$keyword}' a reculé de " . abs($change) . " position(s).";
        }

        return "Le mot-clé '{$keyword}' est resté stable.";
    }
}
