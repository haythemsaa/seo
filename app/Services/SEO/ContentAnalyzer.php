<?php

namespace App\Services\SEO;

use App\Models\Project;
use App\Models\ContentAnalysis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ContentAnalyzer
{
    /**
     * Analyze content for SEO.
     */
    public function analyzeContent(string $url, ?string $targetKeyword = null, ?Project $project = null): array
    {
        // Fetch content
        $html = $this->fetchContent($url);

        // Parse HTML
        $content = $this->parseHtml($html);

        // Analyze
        $analysis = [
            'url' => $url,
            'target_keyword' => $targetKeyword,
            'content_score' => 0,
            'seo_score' => 0,
            'readability_score' => 0,
            'recommendations' => [],
        ];

        // SEO analysis
        $seoAnalysis = $this->analyzeSeo($content, $targetKeyword);
        $analysis['seo_score'] = $seoAnalysis['score'];
        $analysis['recommendations'] = array_merge($analysis['recommendations'], $seoAnalysis['recommendations']);

        // Readability analysis
        $readabilityAnalysis = $this->analyzeReadability($content);
        $analysis['readability_score'] = $readabilityAnalysis['score'];
        $analysis['recommendations'] = array_merge($analysis['recommendations'], $readabilityAnalysis['recommendations']);

        // Keyword analysis
        if ($targetKeyword) {
            $keywordAnalysis = $this->analyzeKeyword($content, $targetKeyword);
            $analysis['keyword_density'] = $keywordAnalysis['density'];
            $analysis['recommendations'] = array_merge($analysis['recommendations'], $keywordAnalysis['recommendations']);
        }

        // Semantic keywords
        $analysis['semantic_keywords'] = $this->extractSemanticKeywords($content, $targetKeyword);

        // Overall content score
        $analysis['content_score'] = (int) (($analysis['seo_score'] + $analysis['readability_score']) / 2);

        // Save to database if project is provided
        if ($project) {
            ContentAnalysis::create([
                'project_id' => $project->id,
                'url' => $url,
                'target_keyword' => $targetKeyword,
                'content_score' => $analysis['content_score'],
                'seo_score' => $analysis['seo_score'],
                'readability_score' => $analysis['readability_score'],
                'recommendations' => $analysis['recommendations'],
                'keyword_density' => $analysis['keyword_density'] ?? null,
                'semantic_keywords' => $analysis['semantic_keywords'],
            ]);
        }

        return $analysis;
    }

    /**
     * Fetch content from URL.
     */
    protected function fetchContent(string $url): string
    {
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch content from {$url}");
        }

        return $response->body();
    }

    /**
     * Parse HTML content.
     */
    protected function parseHtml(string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $data = [];

        // Title
        $titles = $dom->getElementsByTagName('title');
        if ($titles->length > 0) {
            $data['title'] = trim($titles->item(0)->textContent);
        }

        // Meta description
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $meta) {
            if ($meta->getAttribute('name') === 'description') {
                $data['meta_description'] = $meta->getAttribute('content');
            }
        }

        // Headings
        $data['h1'] = [];
        $data['h2'] = [];
        foreach ($dom->getElementsByTagName('h1') as $h1) {
            $data['h1'][] = trim($h1->textContent);
        }
        foreach ($dom->getElementsByTagName('h2') as $h2) {
            $data['h2'][] = trim($h2->textContent);
        }

        // Body text
        $body = $dom->getElementsByTagName('body');
        if ($body->length > 0) {
            $data['body'] = trim(strip_tags($body->item(0)->textContent));
        } else {
            $data['body'] = trim(strip_tags($html));
        }

        // Word count
        $data['word_count'] = str_word_count($data['body']);

        // Paragraph count
        $data['paragraph_count'] = $dom->getElementsByTagName('p')->length;

        // Images
        $data['images_count'] = $dom->getElementsByTagName('img')->length;

        // Links
        $data['links_count'] = $dom->getElementsByTagName('a')->length;

        return $data;
    }

    /**
     * Analyze SEO aspects.
     */
    protected function analyzeSeo(array $content, ?string $targetKeyword): array
    {
        $score = 100;
        $recommendations = [];

        // Title tag
        if (empty($content['title'])) {
            $score -= 20;
            $recommendations[] = [
                'type' => 'critical',
                'message' => 'La balise title est manquante',
            ];
        } elseif (strlen($content['title']) > 60) {
            $score -= 10;
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'La balise title est trop longue (>60 caractères)',
            ];
        } elseif ($targetKeyword && !str_contains(strtolower($content['title']), strtolower($targetKeyword))) {
            $score -= 15;
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Le mot-clé cible n\'apparaît pas dans le title',
            ];
        }

        // Meta description
        if (empty($content['meta_description'])) {
            $score -= 15;
            $recommendations[] = [
                'type' => 'important',
                'message' => 'La meta description est manquante',
            ];
        } elseif (strlen($content['meta_description']) > 160) {
            $score -= 5;
            $recommendations[] = [
                'type' => 'info',
                'message' => 'La meta description est trop longue (>160 caractères)',
            ];
        }

        // H1
        if (empty($content['h1'])) {
            $score -= 15;
            $recommendations[] = [
                'type' => 'important',
                'message' => 'Aucune balise H1 trouvée',
            ];
        } elseif (count($content['h1']) > 1) {
            $score -= 10;
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Plusieurs balises H1 détectées (il devrait y en avoir une seule)',
            ];
        }

        // Content length
        if ($content['word_count'] < 300) {
            $score -= 20;
            $recommendations[] = [
                'type' => 'critical',
                'message' => 'Le contenu est trop court (<300 mots)',
            ];
        } elseif ($content['word_count'] < 600) {
            $score -= 10;
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Le contenu pourrait être plus développé (recommandé: 600+ mots)',
            ];
        }

        // Images
        if ($content['images_count'] === 0 && $content['word_count'] > 500) {
            $score -= 5;
            $recommendations[] = [
                'type' => 'info',
                'message' => 'Aucune image trouvée. Ajouter des images améliore l\'engagement',
            ];
        }

        return [
            'score' => max(0, $score),
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Analyze readability.
     */
    protected function analyzeReadability(array $content): array
    {
        $score = 100;
        $recommendations = [];

        $wordCount = $content['word_count'];
        $paragraphCount = $content['paragraph_count'];

        if ($paragraphCount === 0) {
            return ['score' => 0, 'recommendations' => [['type' => 'critical', 'message' => 'Aucun paragraphe détecté']]];
        }

        // Average words per paragraph
        $avgWordsPerParagraph = $wordCount / $paragraphCount;

        if ($avgWordsPerParagraph > 150) {
            $score -= 20;
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Les paragraphes sont trop longs (moyenne: ' . round($avgWordsPerParagraph) . ' mots)',
            ];
        }

        // Sentence complexity (simplified Flesch Reading Ease approximation)
        $sentences = preg_split('/[.!?]+/', $content['body'], -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);

        if ($sentenceCount > 0) {
            $avgWordsPerSentence = $wordCount / $sentenceCount;

            if ($avgWordsPerSentence > 25) {
                $score -= 15;
                $recommendations[] = [
                    'type' => 'info',
                    'message' => 'Les phrases sont longues (moyenne: ' . round($avgWordsPerSentence) . ' mots). Simplifiez pour améliorer la lisibilité',
                ];
            }
        }

        // Heading structure
        if (count($content['h2']) < 2 && $wordCount > 600) {
            $score -= 10;
            $recommendations[] = [
                'type' => 'info',
                'message' => 'Ajoutez plus de sous-titres (H2) pour structurer le contenu',
            ];
        }

        return [
            'score' => max(0, $score),
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Analyze keyword usage.
     */
    protected function analyzeKeyword(array $content, string $keyword): array
    {
        $recommendations = [];
        $keywordLower = strtolower($keyword);
        $bodyLower = strtolower($content['body']);

        // Count occurrences
        $occurrences = substr_count($bodyLower, $keywordLower);

        // Calculate density
        $density = $content['word_count'] > 0
            ? ($occurrences / $content['word_count']) * 100
            : 0;

        // Optimal density is 1-2%
        if ($density < 0.5) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Le mot-clé apparaît peu dans le contenu (densité: ' . round($density, 2) . '%)',
            ];
        } elseif ($density > 3) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Le mot-clé apparaît trop souvent (densité: ' . round($density, 2) . '%). Risque de sur-optimisation',
            ];
        }

        // Check keyword in first paragraph
        $firstParagraph = Str::limit($content['body'], 300);
        if (!str_contains(strtolower($firstParagraph), $keywordLower)) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'Le mot-clé n\'apparaît pas dans le premier paragraphe',
            ];
        }

        return [
            'density' => [
                'percentage' => round($density, 2),
                'occurrences' => $occurrences,
            ],
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Extract semantic keywords (TF-IDF based).
     */
    protected function extractSemanticKeywords(array $content, ?string $targetKeyword): array
    {
        $body = $content['body'];

        // Simple word frequency analysis
        $words = str_word_count(strtolower($body), 1);

        // Remove common stop words (French)
        $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'et', 'ou', 'mais', 'pour', 'dans', 'sur', 'avec', 'par', 'ce', 'ces', 'est', 'sont'];

        $words = array_filter($words, function ($word) use ($stopWords) {
            return strlen($word) > 3 && !in_array($word, $stopWords);
        });

        // Count frequency
        $frequencies = array_count_values($words);
        arsort($frequencies);

        // Take top 20
        $topWords = array_slice($frequencies, 0, 20, true);

        return array_map(function ($word, $count) {
            return [
                'word' => $word,
                'frequency' => $count,
            ];
        }, array_keys($topWords), $topWords);
    }
}
