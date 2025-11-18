<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasFormattedDates
{
    /**
     * Get formatted created at date.
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Get formatted updated at date.
     */
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    /**
     * Get human readable created at.
     */
    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get human readable updated at.
     */
    public function getUpdatedAtHumanAttribute(): string
    {
        return $this->updated_at->diffForHumans();
    }

    /**
     * Get date for humans from any date field.
     */
    public function getDateForHumans(?string $field = 'created_at'): string
    {
        $date = $this->{$field};

        if (!$date) {
            return 'Jamais';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->diffForHumans();
    }

    /**
     * Get formatted date from any date field.
     */
    public function getFormattedDate(?string $field = 'created_at', string $format = 'd/m/Y H:i'): string
    {
        $date = $this->{$field};

        if (!$date) {
            return '-';
        }

        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date->format($format);
    }
}
