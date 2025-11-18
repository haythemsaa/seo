<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeywordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view keywords for their project.
     */
    public function test_user_can_view_project_keywords(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $keywords = Keyword::factory()->count(5)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get("/projects/{$project->id}/keywords");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Keywords/Index')
                ->has('keywords.data', 5)
        );
    }

    /**
     * Test user can add single keyword.
     */
    public function test_user_can_add_keyword(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $keywordData = [
            'keyword' => 'seo tools',
            'search_volume' => 12000,
            'difficulty' => 65,
            'cpc' => 4.50,
            'target_url' => 'https://example.com/seo-tools',
        ];

        $response = $this->actingAs($user)->post("/projects/{$project->id}/keywords", $keywordData);

        $response->assertRedirect();
        $this->assertDatabaseHas('keywords', [
            'keyword' => 'seo tools',
            'project_id' => $project->id,
        ]);
    }

    /**
     * Test keyword validation.
     */
    public function test_keyword_validation(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("/projects/{$project->id}/keywords", [
            'keyword' => '',
            'difficulty' => 150, // Invalid: > 100
        ]);

        $response->assertSessionHasErrors(['keyword', 'difficulty']);
    }

    /**
     * Test bulk keyword import.
     */
    public function test_user_can_bulk_import_keywords(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $keywords = [
            'seo tools',
            'best seo software',
            'seo analysis',
        ];

        $response = $this->actingAs($user)->post("/projects/{$project->id}/keywords/bulk", [
            'keywords' => $keywords,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('keywords', 3);
    }

    /**
     * Test duplicate keywords are not imported.
     */
    public function test_duplicate_keywords_are_not_imported(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Create existing keyword
        Keyword::factory()->create([
            'project_id' => $project->id,
            'keyword' => 'seo tools',
        ]);

        $response = $this->actingAs($user)->post("/projects/{$project->id}/keywords/bulk", [
            'keywords' => ['seo tools', 'new keyword'],
        ]);

        $response->assertRedirect();
        // Should only have 2 total (1 existing + 1 new)
        $this->assertEquals(2, Keyword::where('project_id', $project->id)->count());
    }

    /**
     * Test user can update keyword.
     */
    public function test_user_can_update_keyword(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $keyword = Keyword::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->put("/keywords/{$keyword->id}", [
            'keyword' => $keyword->keyword,
            'search_volume' => 15000, // Updated
            'difficulty' => 70, // Updated
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('keywords', [
            'id' => $keyword->id,
            'search_volume' => 15000,
            'difficulty' => 70,
        ]);
    }

    /**
     * Test user cannot update keyword from another user's project.
     */
    public function test_user_cannot_update_others_keyword(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $otherUser->id]);
        $keyword = Keyword::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->put("/keywords/{$keyword->id}", [
            'keyword' => 'hacked keyword',
        ]);

        $response->assertForbidden();
    }

    /**
     * Test user can delete keyword.
     */
    public function test_user_can_delete_keyword(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $keyword = Keyword::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->delete("/keywords/{$keyword->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('keywords', ['id' => $keyword->id]);
    }

    /**
     * Test ranking check can be initiated.
     */
    public function test_ranking_check_can_be_initiated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        Keyword::factory()->count(10)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->post("/projects/{$project->id}/keywords/check-rankings");

        $response->assertSuccessful();
        // Verify job was queued
        $this->assertDatabaseHas('jobs', ['queue' => 'rankings']);
    }

    /**
     * Test keywords can be filtered.
     */
    public function test_keywords_can_be_filtered(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Create keywords with different positions
        Keyword::factory()->create(['project_id' => $project->id, 'current_position' => 3]);
        Keyword::factory()->create(['project_id' => $project->id, 'current_position' => 15]);
        Keyword::factory()->create(['project_id' => $project->id, 'current_position' => 25]);

        // Filter top 10
        $response = $this->actingAs($user)->get("/projects/{$project->id}/keywords?position_max=10");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('keywords.data', 1)
        );
    }
}
