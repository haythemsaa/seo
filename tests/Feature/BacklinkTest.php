<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Backlink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BacklinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view backlinks for their project.
     */
    public function test_user_can_view_project_backlinks(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Backlink::factory()->count(10)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Backlinks/Index')
                ->has('backlinks.data', 10)
        );
    }

    /**
     * Test backlinks can be filtered by status.
     */
    public function test_backlinks_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Backlink::factory()->create(['project_id' => $project->id, 'status' => 'active']);
        Backlink::factory()->create(['project_id' => $project->id, 'status' => 'lost']);
        Backlink::factory()->create(['project_id' => $project->id, 'status' => 'toxic']);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks?status=active");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('backlinks.data', 1)
        );
    }

    /**
     * Test backlinks can be filtered by DA.
     */
    public function test_backlinks_can_be_filtered_by_domain_authority(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Backlink::factory()->create(['project_id' => $project->id, 'da' => 30]);
        Backlink::factory()->create(['project_id' => $project->id, 'da' => 50]);
        Backlink::factory()->create(['project_id' => $project->id, 'da' => 70]);

        // Filter DA >= 50
        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks?da_min=50");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('backlinks.data', 2) // 50 and 70
        );
    }

    /**
     * Test user can mark backlink as toxic.
     */
    public function test_user_can_mark_backlink_as_toxic(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $backlink = Backlink::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->post("/backlinks/{$backlink->id}/mark-toxic");

        $response->assertRedirect();
        $this->assertDatabaseHas('backlinks', [
            'id' => $backlink->id,
            'is_toxic' => true,
        ]);
    }

    /**
     * Test user can disavow backlink.
     */
    public function test_user_can_disavow_backlink(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $backlink = Backlink::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->post("/backlinks/{$backlink->id}/disavow");

        $response->assertRedirect();
        $this->assertDatabaseHas('backlinks', [
            'id' => $backlink->id,
            'disavowed' => true,
        ]);
    }

    /**
     * Test user can export disavow file.
     */
    public function test_user_can_export_disavow_file(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Create toxic backlinks
        Backlink::factory()->count(3)->create([
            'project_id' => $project->id,
            'is_toxic' => true,
        ]);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks/export-disavow");

        $response->assertSuccessful();
        $response->assertHeader('Content-Type', 'text/plain');
        $response->assertHeader('Content-Disposition');
    }

    /**
     * Test backlink refresh can be initiated.
     */
    public function test_backlink_refresh_can_be_initiated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/projects/{$project->id}/backlinks/refresh");

        $response->assertSuccessful();
        // Verify job was queued
        $this->assertDatabaseHas('jobs', ['queue' => 'backlinks']);
    }

    /**
     * Test user cannot access other user's backlinks.
     */
    public function test_user_cannot_access_others_backlinks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks");

        $response->assertForbidden();
    }

    /**
     * Test backlink statistics are calculated correctly.
     */
    public function test_backlink_statistics_are_calculated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Backlink::factory()->create(['project_id' => $project->id, 'link_type' => 'dofollow']);
        Backlink::factory()->create(['project_id' => $project->id, 'link_type' => 'dofollow']);
        Backlink::factory()->create(['project_id' => $project->id, 'link_type' => 'nofollow']);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/backlinks");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('stats')
                ->where('stats.total', 3)
                ->where('stats.dofollow', 2)
                ->where('stats.nofollow', 1)
        );
    }
}
