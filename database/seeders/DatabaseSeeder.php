<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Keyword;
use App\Models\Backlink;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@seo-master-pro.fr',
            'password' => bcrypt('password'),
        ]);

        echo "✓ Admin user created: admin@seo-master-pro.fr / password\n";

        // Create demo user with starter plan
        $demoUser = User::factory()->withPlan('starter')->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        echo "✓ Demo user created: demo@example.com / password\n";

        // Create 3 projects for demo user
        $projects = Project::factory()->count(3)->create([
            'user_id' => $demoUser->id,
        ]);

        echo "✓ Created 3 projects for demo user\n";

        // Add keywords to each project
        foreach ($projects as $project) {
            // 20 keywords per project
            $keywords = Keyword::factory()->count(20)->create([
                'project_id' => $project->id,
            ]);

            // Make some keywords in top 10
            Keyword::factory()->topTen()->count(5)->create([
                'project_id' => $project->id,
            ]);

            echo "✓ Created keywords for project: {$project->name}\n";

            // Add backlinks
            Backlink::factory()->count(30)->create([
                'project_id' => $project->id,
            ]);

            // Add some high quality backlinks
            Backlink::factory()->highQuality()->count(10)->create([
                'project_id' => $project->id,
            ]);

            // Add some toxic backlinks
            Backlink::factory()->toxic()->count(5)->create([
                'project_id' => $project->id,
            ]);

            echo "✓ Created backlinks for project: {$project->name}\n";
        }

        // Create additional regular users
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            // Each user gets 1-3 projects
            $projectCount = rand(1, 3);
            $userProjects = Project::factory()->count($projectCount)->create([
                'user_id' => $user->id,
            ]);

            foreach ($userProjects as $project) {
                // 10-30 keywords per project
                Keyword::factory()->count(rand(10, 30))->create([
                    'project_id' => $project->id,
                ]);

                // 20-50 backlinks per project
                Backlink::factory()->count(rand(20, 50))->create([
                    'project_id' => $project->id,
                ]);
            }

            echo "✓ Created projects for user: {$user->name}\n";
        }

        echo "\n";
        echo "========================================\n";
        echo "Database seeding completed successfully!\n";
        echo "========================================\n";
        echo "\n";
        echo "Login credentials:\n";
        echo "  Admin: admin@seo-master-pro.fr / password\n";
        echo "  Demo:  demo@example.com / password\n";
        echo "\n";
        echo "Summary:\n";
        echo "  - Users: " . User::count() . "\n";
        echo "  - Projects: " . Project::count() . "\n";
        echo "  - Keywords: " . Keyword::count() . "\n";
        echo "  - Backlinks: " . Backlink::count() . "\n";
        echo "\n";
    }
}
