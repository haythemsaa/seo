<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordRanking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'keyword_id',
        'project_id',
        'search_engine',
        'device_type',
        'location',
        'position',
        'url',
        'featured_snippet',
        'local_pack',
        'serp_features',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'featured_snippet' => 'boolean',
            'local_pack' => 'boolean',
            'serp_features' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    /**
     * Get the keyword that owns the ranking.
     */
    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }

    /**
     * Get the project that owns the ranking.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if ranking is in top N.
     */
    public function isInTopN(int $n = 10): bool
    {
        return $this->position && $this->position <= $n;
    }

    /**
     * Get ranking tier (top 3, top 10, top 50, etc.).
     */
    public function getTier(): string
    {
        if (!$this->position) {
            return 'not_ranked';
        }

        if ($this->position <= 3) return 'top_3';
        if ($this->position <= 10) return 'top_10';
        if ($this->position <= 20) return 'top_20';
        if ($this->position <= 50) return 'top_50';

        return 'below_50';
    }
}
