<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'subscription_plan',
        'subscription_status',
        'subscription_ends_at',
        'white_label_enabled',
        'white_label_config',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'subscription_ends_at' => 'datetime',
            'white_label_enabled' => 'boolean',
            'white_label_config' => 'array',
            'settings' => 'array',
        ];
    }

    /**
     * Get the owner of the organization.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all users in the organization.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all projects for the organization.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Check if organization has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active' &&
               (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    /**
     * Get the subscription limits for the current plan.
     */
    public function getSubscriptionLimits(): array
    {
        $limits = [
            'free' => [
                'projects' => 1,
                'keywords' => 10,
                'crawl_pages' => 100,
                'reports' => 1,
                'users' => 1,
                'api_calls' => 100,
                'competitors' => 1,
            ],
            'starter' => [
                'projects' => 3,
                'keywords' => 100,
                'crawl_pages' => 5000,
                'reports' => 10,
                'users' => 2,
                'api_calls' => 500,
                'competitors' => 5,
            ],
            'professional' => [
                'projects' => 10,
                'keywords' => 500,
                'crawl_pages' => 50000,
                'reports' => 50,
                'users' => 5,
                'api_calls' => 2000,
                'competitors' => 10,
            ],
            'agency' => [
                'projects' => 50,
                'keywords' => 5000,
                'crawl_pages' => 500000,
                'reports' => 500,
                'users' => 20,
                'api_calls' => 10000,
                'competitors' => 20,
            ],
            'enterprise' => [
                'projects' => -1, // unlimited
                'keywords' => -1,
                'crawl_pages' => -1,
                'reports' => -1,
                'users' => -1,
                'api_calls' => 50000,
                'competitors' => -1,
            ],
        ];

        return $limits[$this->subscription_plan] ?? $limits['free'];
    }
}
