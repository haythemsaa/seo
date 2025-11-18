<?php

namespace App\Services\SEO;

use App\Models\Project;
use App\Models\AiRecommendation;
use App\Models\CrawlSession;
use Illuminate\Support\Facades\Log;

class AiRecommendationEngine
{
    /**
     * Generate recommendations for a project based on crawl data.
     */
    public function generateRecommendations(Project $project): int
    {
        $latestCrawl = $project->latestCrawl;

        if (!$latestCrawl || !$latestCrawl->isCompleted()) {
            throw new \Exception('No completed crawl found for project');
        }

        $count = 0;

        // Technical recommendations
        $count += $this->generateTechnicalRecommendations($project, $latestCrawl);

        // Content recommendations
        $count += $this->generateContentRecommendations($project);

        // Backlink recommendations
        $count += $this->generateBacklinkRecommendations($project);

        // Ranking recommendations
        $count += $this->generateRankingRecommendations($project);

        Log::info("Generated {$count} recommendations for project {$project->id}");

        return $count;
    }

    /**
     * Generate technical SEO recommendations.
     */
    protected function generateTechnicalRecommendations(Project $project, CrawlSession $crawl): int
    {
        $count = 0;

        // Missing title tags
        $missingTitles = $crawl->pages()
            ->whereNull('title')
            ->orWhere('title', '')
            ->count();

        if ($missingTitles > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'technical',
                'priority' => 'critical',
                'title' => "Balises Title manquantes sur {$missingTitles} pages",
                'description' => "Plusieurs pages n'ont pas de balise title, ce qui est critique pour le SEO. Chaque page devrait avoir un titre unique et descriptif de 50-60 caractères.",
                'impact_score' => 95,
                'effort_score' => 40,
                'action_items' => [
                    'Identifier toutes les pages sans balise title',
                    'Rédiger des titres uniques et pertinents pour chaque page',
                    'Vérifier que les titres contiennent les mots-clés principaux',
                    'Limiter la longueur à 50-60 caractères',
                ],
            ]);
            $count++;
        }

        // Missing meta descriptions
        $missingMeta = $crawl->pages()
            ->whereNull('meta_description')
            ->orWhere('meta_description', '')
            ->count();

        if ($missingMeta > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'technical',
                'priority' => 'high',
                'title' => "Meta descriptions manquantes sur {$missingMeta} pages",
                'description' => "Les meta descriptions influencent le taux de clic dans les résultats de recherche. Chaque page devrait avoir une description unique de 150-160 caractères.",
                'impact_score' => 75,
                'effort_score' => 35,
                'action_items' => [
                    'Identifier les pages sans meta description',
                    'Rédiger des descriptions engageantes et pertinentes',
                    'Inclure les mots-clés principaux naturellement',
                    'Respecter la limite de 150-160 caractères',
                ],
            ]);
            $count++;
        }

        // Missing H1 tags
        $missingH1 = $crawl->pages()
            ->whereNull('h1')
            ->orWhere('h1', '')
            ->count();

        if ($missingH1 > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'technical',
                'priority' => 'high',
                'title' => "Balises H1 manquantes sur {$missingH1} pages",
                'description' => "Le H1 est le titre principal de la page et doit être présent sur chaque page. Il aide les moteurs de recherche à comprendre le sujet principal.",
                'impact_score' => 80,
                'effort_score' => 30,
                'action_items' => [
                    'Ajouter une balise H1 unique sur chaque page',
                    'S\'assurer que le H1 décrit le contenu principal',
                    'Inclure le mot-clé principal si pertinent',
                    'Ne pas dupliquer le title tag exactement',
                ],
            ]);
            $count++;
        }

        // Thin content pages
        $thinContent = $crawl->pages()
            ->where('word_count', '<', 300)
            ->where('is_indexable', true)
            ->count();

        if ($thinContent > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'content',
                'priority' => 'medium',
                'title' => "{$thinContent} pages avec contenu insuffisant",
                'description' => "Ces pages contiennent moins de 300 mots, ce qui peut être considéré comme du contenu 'thin' par Google. Enrichissez le contenu pour apporter plus de valeur.",
                'impact_score' => 60,
                'effort_score' => 70,
                'action_items' => [
                    'Identifier les pages avec moins de 300 mots',
                    'Enrichir le contenu avec des informations utiles',
                    'Ajouter des éléments visuels (images, vidéos)',
                    'Considérer la fusion de pages similaires',
                ],
            ]);
            $count++;
        }

        // 404 errors
        $errors404 = $crawl->pages()
            ->where('status_code', 404)
            ->count();

        if ($errors404 > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'technical',
                'priority' => 'critical',
                'title' => "{$errors404} pages retournent une erreur 404",
                'description' => "Des pages retournent une erreur 404 (page non trouvée). Cela nuit à l'expérience utilisateur et au SEO. Corrigez ces erreurs ou mettez en place des redirections.",
                'impact_score' => 90,
                'effort_score' => 50,
                'action_items' => [
                    'Lister toutes les URLs en erreur 404',
                    'Vérifier les liens internes pointant vers ces pages',
                    'Mettre en place des redirections 301 si nécessaire',
                    'Supprimer les liens cassés',
                ],
            ]);
            $count++;
        }

        // Pages without HTTPS
        $noHttps = $crawl->pages()
            ->where('has_https', false)
            ->count();

        if ($noHttps > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'technical',
                'priority' => 'critical',
                'title' => "{$noHttps} pages non sécurisées (HTTP au lieu de HTTPS)",
                'description' => "Le HTTPS est un facteur de ranking et essentiel pour la sécurité. Toutes les pages devraient utiliser HTTPS.",
                'impact_score' => 85,
                'effort_score' => 60,
                'action_items' => [
                    'Installer un certificat SSL',
                    'Forcer les redirections HTTP vers HTTPS',
                    'Mettre à jour les URLs internes',
                    'Vérifier la configuration du serveur',
                ],
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Generate content recommendations.
     */
    protected function generateContentRecommendations(Project $project): int
    {
        $count = 0;

        // Check for duplicate content
        $duplicates = \DB::table('crawled_pages')
            ->where('project_id', $project->id)
            ->select('content_hash', \DB::raw('COUNT(*) as count'))
            ->groupBy('content_hash')
            ->having('count', '>', 1)
            ->count();

        if ($duplicates > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'content',
                'priority' => 'high',
                'title' => "Contenu dupliqué détecté sur {$duplicates} groupes de pages",
                'description' => "Le contenu dupliqué peut diluer votre autorité SEO. Utilisez des canonical tags ou créez du contenu unique.",
                'impact_score' => 70,
                'effort_score' => 80,
                'action_items' => [
                    'Identifier les pages avec contenu similaire',
                    'Ajouter des canonical tags appropriés',
                    'Réécrire le contenu pour le rendre unique',
                    'Considérer la fusion ou suppression de pages',
                ],
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Generate backlink recommendations.
     */
    protected function generateBacklinkRecommendations(Project $project): int
    {
        $count = 0;

        $totalBacklinks = $project->backlinks()->count();
        $activeBacklinks = $project->activeBacklinks()->count();

        // Low backlink count
        if ($totalBacklinks < 100) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'backlink',
                'priority' => 'high',
                'title' => 'Profil de backlinks à développer',
                'description' => "Votre site a actuellement {$totalBacklinks} backlinks. Augmenter ce nombre avec des liens de qualité améliorera votre autorité et vos rankings.",
                'impact_score' => 85,
                'effort_score' => 90,
                'action_items' => [
                    'Créer du contenu linkable (infographies, études)',
                    'Faire du guest blogging sur des sites pertinents',
                    'Participer à des communautés de votre secteur',
                    'Contacter des partenaires pour des échanges de liens',
                    'Soumettre votre site aux annuaires de qualité',
                ],
            ]);
            $count++;
        }

        // Lost backlinks
        $lostBacklinks = $project->backlinks()
            ->whereNotNull('lost_at')
            ->count();

        if ($lostBacklinks > 10) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'backlink',
                'priority' => 'medium',
                'title' => "{$lostBacklinks} backlinks perdus récemment",
                'description' => "Certains de vos backlinks ont été supprimés ou sont devenus inactifs. Tentez de les récupérer ou de les remplacer.",
                'impact_score' => 65,
                'effort_score' => 60,
                'action_items' => [
                    'Identifier les backlinks perdus',
                    'Contacter les webmasters pour comprendre pourquoi',
                    'Proposer du nouveau contenu à lier',
                    'Chercher des alternatives de qualité similaire',
                ],
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Generate ranking recommendations.
     */
    protected function generateRankingRecommendations(Project $project): int
    {
        $count = 0;

        $keywords = $project->keywords()->with('latestRanking')->get();

        if ($keywords->isEmpty()) {
            return 0;
        }

        // Keywords in positions 11-20 (quick wins)
        $quickWins = $keywords->filter(function ($keyword) {
            $ranking = $keyword->latestRanking;
            return $ranking && $ranking->position >= 11 && $ranking->position <= 20;
        });

        if ($quickWins->count() > 0) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'general',
                'priority' => 'medium',
                'title' => "{$quickWins->count()} mots-clés en positions 11-20 (Quick Wins)",
                'description' => "Ces mots-clés sont proches du top 10. Avec un effort ciblé, vous pouvez les faire entrer dans la première page et augmenter significativement votre trafic.",
                'impact_score' => 75,
                'effort_score' => 40,
                'action_items' => [
                    'Optimiser le contenu des pages concernées',
                    'Améliorer les balises title et meta',
                    'Ajouter du contenu pertinent et approfondi',
                    'Obtenir quelques backlinks de qualité',
                    'Améliorer la vitesse de chargement',
                ],
            ]);
            $count++;
        }

        // Keywords not in top 50
        $notRanking = $keywords->filter(function ($keyword) {
            $ranking = $keyword->latestRanking;
            return !$ranking || !$ranking->position || $ranking->position > 50;
        });

        if ($notRanking->count() > $keywords->count() * 0.5) {
            AiRecommendation::create([
                'project_id' => $project->id,
                'type' => 'general',
                'priority' => 'low',
                'title' => "{$notRanking->count()} mots-clés ne sont pas dans le top 50",
                'description' => "Une grande partie de vos mots-clés ne sont pas bien positionnés. Revoyez votre stratégie de contenu et de backlinks.",
                'impact_score' => 80,
                'effort_score' => 95,
                'action_items' => [
                    'Analyser la compétitivité de ces mots-clés',
                    'Considérer des mots-clés moins concurrentiels',
                    'Créer du contenu de qualité ciblé',
                    'Développer une stratégie de backlinks',
                ],
            ]);
            $count++;
        }

        return $count;
    }
}
