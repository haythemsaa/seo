<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine if the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->user_id || $user->role === 'admin';
    }

    /**
     * Determine if the user can create projects.
     */
    public function create(User $user): bool
    {
        // Check subscription limits
        $limits = $this->getSubscriptionLimits($user->subscription_plan);
        $currentProjects = $user->projects()->count();

        return $currentProjects < $limits['projects'];
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can restore the project.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can permanently delete the project.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can initiate a crawl for the project.
     */
    public function crawl(User $user, Project $project): bool
    {
        if ($user->id !== $project->user_id) {
            return false;
        }

        // Check if feature is available in subscription
        $limits = $this->getSubscriptionLimits($user->subscription_plan);

        return $limits['crawl_enabled'];
    }

    /**
     * Get subscription limits for a plan.
     */
    private function getSubscriptionLimits(string $plan): array
    {
        $limits = [
            'free' => [
                'projects' => 1,
                'keywords' => 10,
                'crawl_pages' => 100,
                'crawl_enabled' => true,
            ],
            'starter' => [
                'projects' => 3,
                'keywords' => 100,
                'crawl_pages' => 5000,
                'crawl_enabled' => true,
            ],
            'professional' => [
                'projects' => 10,
                'keywords' => 500,
                'crawl_pages' => 50000,
                'crawl_enabled' => true,
            ],
            'agency' => [
                'projects' => 50,
                'keywords' => 5000,
                'crawl_pages' => 500000,
                'crawl_enabled' => true,
            ],
            'enterprise' => [
                'projects' => PHP_INT_MAX,
                'keywords' => PHP_INT_MAX,
                'crawl_pages' => PHP_INT_MAX,
                'crawl_enabled' => true,
            ],
        ];

        return $limits[$plan] ?? $limits['free'];
    }
}
