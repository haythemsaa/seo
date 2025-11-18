<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'keyword',
        'search_volume',
        'cpc',
        'competition',
        'difficulty_score',
        'search_intent',
        'cluster_id',
        'tags',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'search_volume' => 'integer',
            'cpc' => 'decimal:2',
            'competition' => 'decimal:2',
            'difficulty_score' => 'integer',
            'tags' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the project that owns the keyword.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all rankings for the keyword.
     */
    public function rankings()
    {
        return $this->hasMany(KeywordRanking::class);
    }

    /**
     * Get the latest ranking.
     */
    public function latestRanking()
    {
        return $this->hasOne(KeywordRanking::class)->latestOfMany('checked_at');
    }

    /**
     * Get ranking for a specific device and date.
     */
    public function getRankingForDevice(string $device = 'desktop', ?\DateTime $date = null)
    {
        $query = $this->rankings()->where('device_type', $device);

        if ($date) {
            $query->whereDate('checked_at', $date);
        }

        return $query->latest('checked_at')->first();
    }

    /**
     * Calculate position change compared to previous check.
     */
    public function getPositionChange(): ?int
    {
        $current = $this->latestRanking;
        if (!$current || !$current->position) {
            return null;
        }

        $previous = $this->rankings()
            ->where('id', '!=', $current->id)
            ->where('device_type', $current->device_type)
            ->latest('checked_at')
            ->first();

        if (!$previous || !$previous->position) {
            return null;
        }

        return $previous->position - $current->position;
    }

    /**
     * Check if keyword is in top N positions.
     */
    public function isInTopN(int $n = 10): bool
    {
        $latest = $this->latestRanking;
        return $latest && $latest->position && $latest->position <= $n;
    }
}
