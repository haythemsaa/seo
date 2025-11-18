<template>
    <Head title="Recommandations IA" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">
                        <i class="fas fa-robot text-primary me-2"></i>
                        Recommandations IA
                    </h1>
                    <p class="text-muted mb-0">Recommandations personnalisées pour améliorer votre SEO</p>
                </div>
                <button class="btn btn-gradient-primary" @click="generateRecommendations">
                    <i class="fas fa-sync me-2"></i>
                    Générer de nouvelles recommandations
                </button>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-4" data-aos="fade-up">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="{ active: activeFilter === 'all' }"
                        href="#"
                        @click.prevent="activeFilter = 'all'"
                    >
                        Toutes ({{ mockRecommendations.length }})
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="{ active: activeFilter === 'technical' }"
                        href="#"
                        @click.prevent="activeFilter = 'technical'"
                    >
                        <i class="fas fa-cog me-1"></i>
                        Technique ({{ getCountByType('technical') }})
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="{ active: activeFilter === 'content' }"
                        href="#"
                        @click.prevent="activeFilter = 'content'"
                    >
                        <i class="fas fa-file-alt me-1"></i>
                        Contenu ({{ getCountByType('content') }})
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="{ active: activeFilter === 'backlinks' }"
                        href="#"
                        @click.prevent="activeFilter = 'backlinks'"
                    >
                        <i class="fas fa-link me-1"></i>
                        Backlinks ({{ getCountByType('backlinks') }})
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="{ active: activeFilter === 'rankings' }"
                        href="#"
                        @click.prevent="activeFilter = 'rankings'"
                    >
                        <i class="fas fa-chart-line me-1"></i>
                        Positions ({{ getCountByType('rankings') }})
                    </a>
                </li>
            </ul>

            <!-- Impact/Effort Matrix -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="fas fa-rocket text-success me-2"></i>
                                Quick Wins (Impact élevé, Effort faible)
                            </h5>
                            <div v-if="quickWins.length === 0" class="text-center text-muted py-3">
                                Aucune recommandation quick win
                            </div>
                            <div v-else class="list-group list-group-flush">
                                <div
                                    v-for="rec in quickWins.slice(0, 3)"
                                    :key="rec.id"
                                    class="list-group-item px-0 border-0"
                                >
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ rec.title }}</h6>
                                            <small class="text-muted">{{ rec.description }}</small>
                                        </div>
                                        <span class="badge bg-success">{{ rec.impact }}/100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="fas fa-fire text-danger me-2"></i>
                                Priorité haute (Impact élevé)
                            </h5>
                            <div v-if="highPriority.length === 0" class="text-center text-muted py-3">
                                Aucune recommandation haute priorité
                            </div>
                            <div v-else class="list-group list-group-flush">
                                <div
                                    v-for="rec in highPriority.slice(0, 3)"
                                    :key="rec.id"
                                    class="list-group-item px-0 border-0"
                                >
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ rec.title }}</h6>
                                            <small class="text-muted">{{ rec.description }}</small>
                                        </div>
                                        <span class="badge bg-danger">{{ rec.impact }}/100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Recommendations -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body">
                    <div v-if="filteredRecommendations.length === 0" class="text-center py-5 text-muted">
                        <i class="fas fa-lightbulb fs-1 mb-3 d-block"></i>
                        Aucune recommandation pour le moment
                    </div>
                    <div v-else class="row g-3">
                        <div
                            v-for="(rec, index) in filteredRecommendations"
                            :key="rec.id"
                            class="col-12"
                            data-aos="fade-up"
                            :data-aos-delay="index * 50"
                        >
                            <div class="card h-100 border hover-lift">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge me-2" :class="getTypeClass(rec.type)">
                                                    {{ getTypeLabel(rec.type) }}
                                                </span>
                                                <span class="badge bg-light text-dark">{{ rec.project }}</span>
                                            </div>
                                            <h5 class="card-title fw-bold mb-2">{{ rec.title }}</h5>
                                            <p class="card-text text-muted mb-0">{{ rec.description }}</p>
                                        </div>
                                        <button
                                            class="btn btn-sm"
                                            :class="rec.status === 'completed' ? 'btn-success' : 'btn-outline-primary'"
                                            @click="toggleStatus(rec)"
                                        >
                                            <i :class="rec.status === 'completed' ? 'fas fa-check' : 'far fa-circle'"></i>
                                        </button>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block mb-1">Impact</small>
                                            <div class="progress" style="height: 8px;">
                                                <div
                                                    class="progress-bar bg-success"
                                                    :style="{ width: rec.impact + '%' }"
                                                ></div>
                                            </div>
                                            <small class="text-muted">{{ rec.impact }}/100</small>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block mb-1">Effort</small>
                                            <div class="progress" style="height: 8px;">
                                                <div
                                                    class="progress-bar bg-warning"
                                                    :style="{ width: rec.effort + '%' }"
                                                ></div>
                                            </div>
                                            <small class="text-muted">{{ rec.effort }}/100</small>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block mb-1">Priorité</small>
                                            <div class="d-flex align-items-center">
                                                <span class="badge" :class="getPriorityClass(rec.priority)">
                                                    {{ rec.priority }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="rec.action_items" class="mb-3">
                                        <small class="text-muted fw-semibold d-block mb-2">Actions à effectuer :</small>
                                        <ul class="small mb-0">
                                            <li v-for="(item, idx) in rec.action_items" :key="idx">{{ item }}</li>
                                        </ul>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ formatDate(rec.created_at) }}
                                        </small>
                                        <button class="btn btn-sm btn-outline-primary" @click="viewDetails(rec)">
                                            <i class="fas fa-arrow-right me-1"></i>
                                            Voir détails
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const activeFilter = ref('all');

// Mock data
const mockRecommendations = [
    {
        id: 1,
        type: 'technical',
        project: 'Site Principal',
        title: 'Optimiser les balises title manquantes',
        description: '15 pages n\'ont pas de balise title. Cela nuit gravement à votre référencement.',
        impact: 95,
        effort: 40,
        priority: 'haute',
        status: 'pending',
        action_items: [
            'Identifier les 15 pages sans title',
            'Créer des titles uniques et descriptifs',
            'Implémenter les changements',
            'Vérifier avec Google Search Console'
        ],
        created_at: '2025-01-15'
    },
    {
        id: 2,
        type: 'content',
        title: 'Enrichir le contenu des pages minces',
        description: '8 pages ont moins de 300 mots. Augmentez le contenu pour améliorer le positionnement.',
        impact: 75,
        effort: 80,
        priority: 'moyenne',
        project: 'Blog',
        status: 'pending',
        action_items: [
            'Analyser les 8 pages concernées',
            'Rechercher des sujets complémentaires',
            'Rédiger du contenu de qualité (min 800 mots)',
            'Optimiser pour les mots-clés cibles'
        ],
        created_at: '2025-01-14'
    },
    {
        id: 3,
        type: 'backlinks',
        title: 'Récupérer les backlinks perdus',
        description: '12 backlinks de qualité ont été perdus ce mois-ci. Contactez les webmasters.',
        impact: 70,
        effort: 60,
        priority: 'moyenne',
        project: 'Site Principal',
        status: 'pending',
        action_items: [
            'Identifier les 12 backlinks perdus',
            'Analyser la cause de la perte',
            'Contacter les webmasters',
            'Proposer du nouveau contenu'
        ],
        created_at: '2025-01-13'
    },
    {
        id: 4,
        type: 'rankings',
        title: 'Cibler les mots-clés en position 11-20',
        description: '25 mots-clés sont juste en dessous de la 1ère page. Un petit effort peut les faire monter.',
        impact: 85,
        effort: 55,
        priority: 'haute',
        project: 'Site Principal',
        status: 'pending',
        action_items: [
            'Lister les 25 mots-clés concernés',
            'Analyser la concurrence en top 10',
            'Optimiser le contenu existant',
            'Créer des liens internes'
        ],
        created_at: '2025-01-12'
    },
    {
        id: 5,
        type: 'technical',
        title: 'Corriger les erreurs 404',
        description: '23 pages retournent une erreur 404. Créez des redirections 301.',
        impact: 60,
        effort: 30,
        priority: 'moyenne',
        project: 'Site Principal',
        status: 'completed',
        action_items: [
            'Lister toutes les URLs en 404',
            'Trouver les pages de remplacement',
            'Mettre en place les redirections 301',
            'Vérifier dans Google Search Console'
        ],
        created_at: '2025-01-10'
    },
];

const filteredRecommendations = computed(() => {
    if (activeFilter.value === 'all') return mockRecommendations;
    return mockRecommendations.filter(r => r.type === activeFilter.value);
});

const quickWins = computed(() => {
    return mockRecommendations.filter(r => r.impact >= 70 && r.effort <= 50);
});

const highPriority = computed(() => {
    return mockRecommendations.filter(r => r.impact >= 80);
});

const getCountByType = (type) => {
    return mockRecommendations.filter(r => r.type === type).length;
};

const getTypeClass = (type) => {
    const classes = {
        technical: 'bg-primary',
        content: 'bg-warning',
        backlinks: 'bg-info',
        rankings: 'bg-success',
    };
    return classes[type] || 'bg-secondary';
};

const getTypeLabel = (type) => {
    const labels = {
        technical: 'Technique',
        content: 'Contenu',
        backlinks: 'Backlinks',
        rankings: 'Positions',
    };
    return labels[type] || type;
};

const getPriorityClass = (priority) => {
    const classes = {
        haute: 'bg-danger',
        moyenne: 'bg-warning',
        basse: 'bg-secondary',
    };
    return classes[priority] || 'bg-secondary';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const toggleStatus = (rec) => {
    rec.status = rec.status === 'completed' ? 'pending' : 'completed';
    window.$toast?.success(
        rec.status === 'completed' ? 'Recommandation marquée comme terminée' : 'Recommandation marquée comme en attente'
    );
};

const generateRecommendations = () => {
    window.$toast?.info('Génération de nouvelles recommandations...');
};

const viewDetails = (rec) => {
    window.$toast?.info(`Détails de "${rec.title}" (à implémenter)`);
};
</script>

<style scoped>
.btn-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    color: white;
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}
</style>
