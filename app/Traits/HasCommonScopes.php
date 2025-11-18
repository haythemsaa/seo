<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCommonScopes
{
    /**
     * Scope a query to only include active records.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive records.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', '!=', 'active');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeWhereStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to get recent records.
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to search by term.
     */
    public function scopeSearch(Builder $query, ?string $term, array $columns = ['name']): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($query) use ($term, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$term}%");
            }
        });
    }

    /**
     * Scope a query to order by latest.
     */
    public function scopeLatest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderBy($column, 'desc');
    }

    /**
     * Scope a query to order by oldest.
     */
    public function scopeOldest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderBy($column, 'asc');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange(Builder $query, ?string $from = null, ?string $to = null, string $column = 'created_at'): Builder
    {
        if ($from) {
            $query->where($column, '>=', $from);
        }

        if ($to) {
            $query->where($column, '<=', $to);
        }

        return $query;
    }

    /**
     * Scope a query to get records created today.
     */
    public function scopeToday(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->whereDate($column, today());
    }

    /**
     * Scope a query to get records created this week.
     */
    public function scopeThisWeek(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to get records created this month.
     */
    public function scopeThisMonth(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->whereMonth($column, now()->month)
            ->whereYear($column, now()->year);
    }
}
