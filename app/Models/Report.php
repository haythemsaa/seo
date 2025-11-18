<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'organization_id',
        'type',
        'period_start',
        'period_end',
        'title',
        'data',
        'pdf_path',
        'scheduled',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'data' => 'array',
            'scheduled' => 'boolean',
            'sent_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the project that owns the report.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the organization that owns the report.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if report has been sent.
     */
    public function hasBeenSent(): bool
    {
        return !is_null($this->sent_at);
    }

    /**
     * Get the PDF download URL.
     */
    public function getPdfUrl(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        return asset('storage/' . $this->pdf_path);
    }
}
