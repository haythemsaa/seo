<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view their projects.
     */
    public function test_user_can_view_their_projects(): void
    {
        $user = User::factory()->create();
        $projects = Project::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/projects');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Projects/Index')
                ->has('projects.data', 3)
        );
    }

    /**
     * Test user can create a new project.
     */
    public function test_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $projectData = [
            'name' => 'Test Project',
            'url' => 'https://example.com',
            'country' => 'FR',
            'language' => 'fr',
            'search_engine' => 'google',
        ];

        $response = $this->actingAs($user)->post('/projects', $projectData);

        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'url' => 'https://example.com',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test project creation validation.
     */
    public function test_project_creation_requires_valid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/projects', [
            'name' => '',
            'url' => 'invalid-url',
        ]);

        $response->assertSessionHasErrors(['name', 'url', 'country', 'language']);
    }

    /**
     * Test user can update their project.
     */
    public function test_user_can_update_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/projects/{$project->id}", [
            'name' => 'Updated Project Name',
            'url' => $project->url,
            'country' => $project->country,
            'language' => $project->language,
            'search_engine' => $project->search_engine,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project Name',
        ]);
    }

    /**
     * Test user cannot update another user's project.
     */
    public function test_user_cannot_update_others_project(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put("/projects/{$project->id}", [
            'name' => 'Hacked Project',
            'url' => $project->url,
            'country' => $project->country,
            'language' => $project->language,
        ]);

        $response->assertForbidden();
    }

    /**
     * Test user can delete their project.
     */
    public function test_user_can_delete_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/projects/{$project->id}");

        $response->assertRedirect('/projects');
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    /**
     * Test guest cannot access projects.
     */
    public function test_guest_cannot_access_projects(): void
    {
        $response = $this->get('/projects');

        $response->assertRedirect('/login');
    }

    /**
     * Test project crawl can be initiated.
     */
    public function test_user_can_initiate_project_crawl(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/projects/{$project->id}/crawl");

        $response->assertSuccessful();
        $this->assertDatabaseHas('crawl_sessions', [
            'project_id' => $project->id,
            'status' => 'pending',
        ]);
    }
}
