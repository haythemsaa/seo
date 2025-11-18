<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test API authentication required.
     */
    public function test_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/projects');

        $response->assertUnauthorized();
    }

    /**
     * Test API can list projects.
     */
    public function test_api_can_list_projects(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Project::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'url',
                        'country',
                        'language',
                        'created_at',
                    ],
                ],
                'meta' => [
                    'current_page',
                    'total',
                ],
            ])
            ->assertJsonCount(5, 'data');
    }

    /**
     * Test API can create project.
     */
    public function test_api_can_create_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $projectData = [
            'name' => 'API Test Project',
            'url' => 'https://api-test.com',
            'country' => 'FR',
            'language' => 'fr',
            'search_engine' => 'google',
        ];

        $response = $this->postJson('/api/v1/projects', $projectData);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'url',
                ],
            ])
            ->assertJson([
                'data' => [
                    'name' => 'API Test Project',
                    'url' => 'https://api-test.com',
                ],
            ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'API Test Project',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test API validation errors.
     */
    public function test_api_returns_validation_errors(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/projects', [
            'name' => '',
            'url' => 'invalid',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'url', 'country', 'language']);
    }

    /**
     * Test API can show single project.
     */
    public function test_api_can_show_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/v1/projects/{$project->id}");

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name,
                ],
            ]);
    }

    /**
     * Test API can update project.
     */
    public function test_api_can_update_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated via API',
            'url' => $project->url,
            'country' => $project->country,
            'language' => $project->language,
        ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'name' => 'Updated via API',
                ],
            ]);
    }

    /**
     * Test API can delete project.
     */
    public function test_api_can_delete_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertNoContent();
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    /**
     * Test API rate limiting.
     */
    public function test_api_rate_limiting(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Make requests up to the limit
        for ($i = 0; $i < 61; $i++) {
            $response = $this->getJson('/api/v1/projects');

            if ($i < 60) {
                $response->assertOk();
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }
}
