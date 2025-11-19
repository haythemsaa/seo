<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Keyword;
use App\Models\Backlink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Create admin user
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@seo-master-pro.com',
            'password' => bcrypt('password'),
            'subscription_plan' => 'enterprise',
            'subscription_status' => 'active',
            'notification_preferences' => [
                'ranking_changes' => true,
                'backlink_found' => true,
                'audit_completed' => true,
                'weekly_report' => true,
            ],
        ]);

        $this->command->info("✓ Admin user created: admin@seo-master-pro.com / password (Enterprise Plan)");

        // Create demo user with starter plan and realistic data
        $demoUser = User::factory()->withPlan('starter')->create([
            'name' => 'Jean Dupont',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'company' => 'Agence Web Moderne',
            'notification_preferences' => [
                'ranking_changes' => true,
                'backlink_found' => true,
                'audit_completed' => true,
                'weekly_report' => false,
            ],
        ]);

        $this->command->info("✓ Demo user created: demo@example.com / password (Starter Plan)");
        $this->command->newLine();

        // Create realistic demo projects with detailed data
        $this->command->info('📁 Creating demo projects with complete data...');

        $demoProjects = [
            [
                'name' => 'Site E-commerce Mode',
                'url' => 'https://boutique-mode-exemple.fr',
                'description' => 'Boutique en ligne de vêtements et accessoires de mode',
                'target_country' => 'FR',
                'target_language' => 'fr',
                'status' => 'active',
                'keywords' => [
                    ['keyword' => 'vêtements femme en ligne', 'position' => 12, 'volume' => 8100],
                    ['keyword' => 'boutique mode paris', 'position' => 8, 'volume' => 1900],
                    ['keyword' => 'robe soirée élégante', 'position' => 15, 'volume' => 5400],
                    ['keyword' => 'accessoires mode tendance', 'position' => 22, 'volume' => 3200],
                    ['keyword' => 'chaussures femme confort', 'position' => 5, 'volume' => 6700],
                    ['keyword' => 'mode éthique française', 'position' => 7, 'volume' => 2100],
                    ['keyword' => 'collection printemps été', 'position' => 18, 'volume' => 4300],
                    ['keyword' => 'sac à main cuir', 'position' => 11, 'volume' => 7200],
                ],
                'backlinks' => 45,
                'domain_authority' => 42,
            ],
            [
                'name' => 'Blog Cuisine & Recettes',
                'url' => 'https://saveurs-et-recettes.fr',
                'description' => 'Blog culinaire avec recettes traditionnelles et modernes',
                'target_country' => 'FR',
                'target_language' => 'fr',
                'status' => 'active',
                'keywords' => [
                    ['keyword' => 'recette tarte tatin facile', 'position' => 3, 'volume' => 12000],
                    ['keyword' => 'cuisine française traditionnelle', 'position' => 9, 'volume' => 4500],
                    ['keyword' => 'desserts rapides maison', 'position' => 6, 'volume' => 8900],
                    ['keyword' => 'plats végétariens sains', 'position' => 14, 'volume' => 6200],
                    ['keyword' => 'recette boeuf bourguignon', 'position' => 4, 'volume' => 15000],
                    ['keyword' => 'pâtisserie française facile', 'position' => 10, 'volume' => 5100],
                ],
                'backlinks' => 78,
                'domain_authority' => 58,
            ],
            [
                'name' => 'Cabinet Conseil SEO',
                'url' => 'https://conseil-seo-expert.fr',
                'description' => 'Agence spécialisée en référencement naturel et stratégie digitale',
                'target_country' => 'FR',
                'target_language' => 'fr',
                'status' => 'active',
                'keywords' => [
                    ['keyword' => 'consultant seo paris', 'position' => 7, 'volume' => 1300],
                    ['keyword' => 'audit seo complet', 'position' => 11, 'volume' => 2800],
                    ['keyword' => 'agence référencement naturel', 'position' => 15, 'volume' => 4100],
                    ['keyword' => 'optimisation seo google', 'position' => 9, 'volume' => 3600],
                    ['keyword' => 'stratégie contenu seo', 'position' => 13, 'volume' => 1900],
                ],
                'backlinks' => 32,
                'domain_authority' => 35,
            ],
        ];

        foreach ($demoProjects as $projectData) {
            $project = Project::create([
                'user_id' => $demoUser->id,
                'name' => $projectData['name'],
                'url' => $projectData['url'],
                'description' => $projectData['description'],
                'target_country' => $projectData['target_country'],
                'target_language' => $projectData['target_language'],
                'status' => $projectData['status'],
            ]);

            $this->command->info("  ✓ Project: {$project->name}");

            // Add keywords with realistic positions and search volumes
            foreach ($projectData['keywords'] as $keywordData) {
                Keyword::create([
                    'project_id' => $project->id,
                    'keyword' => $keywordData['keyword'],
                    'target_url' => $project->url,
                    'search_engine' => 'google',
                    'country' => 'FR',
                    'language' => 'fr',
                    'device' => 'desktop',
                    'current_position' => $keywordData['position'],
                    'previous_position' => $keywordData['position'] + rand(-5, 5),
                    'best_position' => max(1, $keywordData['position'] - rand(0, 8)),
                    'worst_position' => min(100, $keywordData['position'] + rand(0, 15)),
                    'search_volume' => $keywordData['volume'],
                    'last_check' => now()->subHours(rand(1, 48)),
                ]);
            }

            $this->command->info("    → {$project->keywords()->count()} keywords added");

            // Add realistic backlinks
            $backlinkCount = $projectData['backlinks'];
            for ($i = 0; $i < $backlinkCount; $i++) {
                $isHighQuality = $i < ($backlinkCount * 0.3); // 30% high quality
                $isToxic = $i >= ($backlinkCount * 0.9); // 10% toxic

                Backlink::create([
                    'project_id' => $project->id,
                    'source_url' => 'https://site-externe-' . rand(1, 500) . '.fr/article',
                    'target_url' => $project->url . '/' . ['blog', 'products', 'about', 'contact'][rand(0, 3)],
                    'anchor_text' => ['cliquez ici', 'en savoir plus', 'découvrir', $project->name][rand(0, 3)],
                    'rel_attribute' => $isToxic ? 'nofollow' : ($isHighQuality && rand(0, 1) ? 'dofollow' : 'nofollow'),
                    'status' => $isToxic ? 'lost' : 'active',
                    'domain_authority' => $isHighQuality ? rand(60, 95) : ($isToxic ? rand(1, 20) : rand(25, 55)),
                    'page_authority' => $isHighQuality ? rand(55, 90) : ($isToxic ? rand(1, 15) : rand(20, 50)),
                    'spam_score' => $isToxic ? rand(40, 95) : rand(1, 15),
                    'first_seen' => now()->subDays(rand(1, 180)),
                    'last_checked' => now()->subHours(rand(1, 72)),
                ]);
            }

            $this->command->info("    → {$project->backlinks()->count()} backlinks added");
        }

        $this->command->newLine();

        // Create professional users with different plans
        $this->command->info('👥 Creating additional users with various subscription plans...');

        $professionalUser = User::factory()->withPlan('professional')->create([
            'name' => 'Marie Martin',
            'email' => 'marie@marketing-agency.fr',
            'company' => 'Digital Marketing Pro',
            'password' => bcrypt('password'),
        ]);

        $agencyUser = User::factory()->withPlan('agency')->create([
            'name' => 'Pierre Dubois',
            'email' => 'pierre@agence-web.fr',
            'company' => 'Agence Web Excellence',
            'password' => bcrypt('password'),
        ]);

        $freeUser = User::factory()->withPlan('free')->create([
            'name' => 'Sophie Bernard',
            'email' => 'sophie@freelance.fr',
            'company' => 'Freelance SEO',
            'password' => bcrypt('password'),
        ]);

        $this->command->info("  ✓ Professional user: marie@marketing-agency.fr / password");
        $this->command->info("  ✓ Agency user: pierre@agence-web.fr / password");
        $this->command->info("  ✓ Free user: sophie@freelance.fr / password");

        // Create projects for professional users
        foreach ([$professionalUser, $agencyUser] as $user) {
            $projectCount = $user->subscription_plan === 'agency' ? 5 : 3;

            for ($i = 0; $i < $projectCount; $i++) {
                $project = Project::factory()->create([
                    'user_id' => $user->id,
                ]);

                $keywordCount = $user->subscription_plan === 'agency' ? rand(30, 50) : rand(15, 25);
                Keyword::factory()->count($keywordCount)->create([
                    'project_id' => $project->id,
                ]);

                $backlinkCount = rand(20, 40);
                Backlink::factory()->count($backlinkCount)->create([
                    'project_id' => $project->id,
                ]);
            }
        }

        // Create limited project for free user
        $freeProject = Project::factory()->create([
            'user_id' => $freeUser->id,
            'name' => 'Mon Premier Site',
        ]);

        Keyword::factory()->count(8)->create([
            'project_id' => $freeProject->id,
        ]);

        Backlink::factory()->count(12)->create([
            'project_id' => $freeProject->id,
        ]);

        $this->command->newLine();

        // Display summary
        $this->command->info('========================================');
        $this->command->info(' 🎉 Database Seeding Completed Successfully!');
        $this->command->info('========================================');
        $this->command->newLine();

        $this->command->table(
            ['Metric', 'Count'],
            [
                ['Users', User::count()],
                ['Projects', Project::count()],
                ['Keywords', Keyword::count()],
                ['Backlinks', Backlink::count()],
            ]
        );

        $this->command->newLine();
        $this->command->info('📋 Login Credentials:');
        $this->command->newLine();

        $this->command->table(
            ['Email', 'Password', 'Plan', 'Description'],
            [
                ['admin@seo-master-pro.com', 'password', 'Enterprise', 'Full admin access'],
                ['demo@example.com', 'password', 'Starter', 'Demo account with sample data'],
                ['marie@marketing-agency.fr', 'password', 'Professional', 'Marketing agency'],
                ['pierre@agence-web.fr', 'password', 'Agency', 'Web agency with multiple clients'],
                ['sophie@freelance.fr', 'password', 'Free', 'Freelancer with limited features'],
            ]
        );

        $this->command->newLine();
        $this->command->info('🚀 You can now start using SEO Master Pro!');
        $this->command->info('   Run: php artisan serve');
        $this->command->info('   Access: http://localhost:8000');
        $this->command->newLine();
    }
}
