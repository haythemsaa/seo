<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'type',
        'priority',
        'title',
        'description',
        'impact_score',
        'effort_score',
        'affected_urls',
        'action_items',
        'status',
        'generated_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'impact_score' => 'integer',
            'effort_score' => 'integer',
            'affected_urls' => 'array',
            'action_items' => 'array',
            'generated_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the project that owns the recommendation.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Calculate ROI score (Impact vs Effort).
     */
    public function getRoiScore(): float
    {
        if (!$this->effort_score || $this->effort_score === 0) {
            return 0;
        }

        return round($this->impact_score / $this->effort_score, 2);
    }

    /**
     * Check if this is a quick win (high impact, low effort).
     */
    public function isQuickWin(): bool
    {
        return $this->impact_score >= 70 && $this->effort_score <= 30;
    }

    /**
     * Mark recommendation as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
