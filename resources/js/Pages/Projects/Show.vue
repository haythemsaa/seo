<template>
    <Head :title="project.name" />

    <AppLayout>
        <div class="container-fluid py-4">
            <!-- Project Header -->
            <div class="mb-4" data-aos="fade-down">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2">
                                <li class="breadcrumb-item"><Link href="/projects">Projets</Link></li>
                                <li class="breadcrumb-item active">{{ project.name }}</li>
                            </ol>
                        </nav>
                        <h1 class="h2 fw-bold mb-1">{{ project.name }}</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-globe me-1"></i>
                            <a :href="project.website_url" target="_blank" class="text-decoration-none">
                                {{ project.website_url }}
                            </a>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" @click="editProject">
                            <i class="fas fa-edit me-2"></i>
                            Modifier
                        </button>
                        <button class="btn btn-gradient-primary" @click="crawlNow">
                            <i class="fas fa-sync me-2"></i>
                            Analyser maintenant
                        </button>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-primary me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Visibilité</p>
                                    <h3 class="fw-bold mb-0">{{ project.visibility_score }}%</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        +{{ project.visibility_change }}%
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-success me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-key text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Mots-clés suivis</p>
                                    <h3 class="fw-bold mb-0">{{ project.keywords_count }}</h3>
                                    <small class="text-muted">Top 10: {{ project.top10_count }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-info me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-link text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Backlinks</p>
                                    <h3 class="fw-bold mb-0">{{ project.backlinks_count }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-plus me-1"></i>
                                        +{{ project.new_backlinks }} ce mois
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-warning me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Problèmes SEO</p>
                                    <h3 class="fw-bold mb-0 text-warning">{{ project.issues_count }}</h3>
                                    <small class="text-muted">{{ project.critical_issues }} critiques</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-pills mb-4" data-aos="fade-up">
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'overview' }" href="#" @click.prevent="activeTab = 'overview'">
                        <i class="fas fa-chart-pie me-1"></i>
                        Vue d'ensemble
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'keywords' }" href="#" @click.prevent="activeTab = 'keywords'">
                        <i class="fas fa-key me-1"></i>
                        Mots-clés ({{ project.keywords_count }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'backlinks' }" href="#" @click.prevent="activeTab = 'backlinks'">
                        <i class="fas fa-link me-1"></i>
                        Backlinks ({{ project.backlinks_count }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'audits' }" href="#" @click.prevent="activeTab = 'audits'">
                        <i class="fas fa-search me-1"></i>
                        Audits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :class="{ active: activeTab === 'settings' }" href="#" @click.prevent="activeTab = 'settings'">
                        <i class="fas fa-cog me-1"></i>
                        Configuration
                    </a>
                </li>
            </ul>

            <!-- Overview Tab -->
            <div v-if="activeTab === 'overview'">
                <!-- Visibility Chart -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Évolution de la visibilité (30 jours)</h5>
                    </div>
                    <div class="card-body">
                        <canvas ref="visibilityChart" height="80"></canvas>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!-- Top Keywords -->
                    <div class="col-lg-6" data-aos="fade-up">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold">Top mots-clés</h5>
                                    <Link href="/keywords" class="btn btn-sm btn-outline-primary">Voir tout</Link>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <tbody>
                                            <tr v-for="keyword in topKeywords" :key="keyword.id">
                                                <td class="px-4 py-3">
                                                    <div class="fw-semibold">{{ keyword.keyword }}</div>
                                                    <small class="text-muted">{{ keyword.volume }} vol.</small>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-success rounded-pill">{{ keyword.position }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-end">
                                                    <small :class="keyword.change > 0 ? 'text-success' : 'text-danger'">
                                                        <i :class="keyword.change > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                                        {{ Math.abs(keyword.change) }}
                                                    </small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Issues -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold">Problèmes récents</h5>
                                    <Link href="/audits" class="btn btn-sm btn-outline-primary">Voir audit</Link>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div v-for="issue in recentIssues" :key="issue.id" class="list-group-item px-0 border-0">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="icon-shape" :class="getSeverityClass(issue.severity)" style="width: 32px; height: 32px;">
                                                    <i class="text-white small" :class="getSeverityIcon(issue.severity)"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 small fw-semibold">{{ issue.title }}</h6>
                                                <small class="text-muted">{{ issue.affected_pages }} pages affectées</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-robot text-primary me-2"></i>
                                Recommandations IA
                            </h5>
                            <Link href="/recommendations" class="btn btn-sm btn-outline-primary">Voir tout</Link>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div v-for="rec in recommendations" :key="rec.id" class="col-md-6">
                                <div class="border rounded p-3 h-100 hover-bg">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge" :class="getTypeClass(rec.type)">{{ rec.type }}</span>
                                        <span class="badge bg-success">Impact: {{ rec.impact }}/100</span>
                                    </div>
                                    <h6 class="fw-semibold mb-2">{{ rec.title }}</h6>
                                    <p class="small text-muted mb-0">{{ rec.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keywords Tab -->
            <div v-if="activeTab === 'keywords'">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-center text-muted">
                            <i class="fas fa-arrow-right me-2"></i>
                            <Link href="/keywords" class="text-decoration-none">Voir tous les mots-clés de ce projet</Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Backlinks Tab -->
            <div v-if="activeTab === 'backlinks'">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-center text-muted">
                            <i class="fas fa-arrow-right me-2"></i>
                            <Link href="/backlinks" class="text-decoration-none">Voir tous les backlinks de ce projet</Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Audits Tab -->
            <div v-if="activeTab === 'audits'">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <p class="text-center text-muted">
                            <i class="fas fa-arrow-right me-2"></i>
                            <Link href="/audits" class="text-decoration-none">Voir l'historique des audits</Link>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div v-if="activeTab === 'settings'">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Configuration du projet</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="updateProject">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nom du projet</label>
                                    <input type="text" v-model="settingsForm.name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">URL du site</label>
                                    <input type="url" v-model="settingsForm.website_url" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Pays</label>
                                    <select v-model="settingsForm.country" class="form-select">
                                        <option value="FR">France</option>
                                        <option value="BE">Belgique</option>
                                        <option value="CH">Suisse</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Langue</label>
                                    <select v-model="settingsForm.language" class="form-select">
                                        <option value="fr-FR">Français</option>
                                        <option value="en-US">Anglais</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fréquence d'analyse</label>
                                    <select v-model="settingsForm.crawl_frequency" class="form-select">
                                        <option value="daily">Quotidienne</option>
                                        <option value="weekly">Hebdomadaire</option>
                                        <option value="monthly">Mensuelle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="settingsForm.is_active" id="is_active">
                                    <label class="form-check-label" for="is_active">
                                        Projet actif (suivi automatique)
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-danger" @click="deleteProject">
                                    <i class="fas fa-trash me-2"></i>
                                    Supprimer le projet
                                </button>
                                <button type="submit" class="btn btn-gradient-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Chart } from 'chart.js';

const props = defineProps({
    project: {
        type: Object,
        default: () => ({
            id: 1,
            name: 'Site Principal',
            website_url: 'https://example.com',
            visibility_score: 45,
            visibility_change: 12.5,
            keywords_count: 127,
            top10_count: 42,
            backlinks_count: 1234,
            new_backlinks: 23,
            issues_count: 25,
            critical_issues: 5,
        }),
    },
});

const activeTab = ref('overview');
const visibilityChart = ref(null);

const settingsForm = ref({
    name: props.project.name,
    website_url: props.project.website_url,
    country: 'FR',
    language: 'fr-FR',
    crawl_frequency: 'weekly',
    is_active: true,
});

const topKeywords = [
    { id: 1, keyword: 'agence seo paris', position: 2, volume: 1200, change: 3 },
    { id: 2, keyword: 'référencement naturel', position: 5, volume: 2500, change: -1 },
    { id: 3, keyword: 'consultant seo', position: 8, volume: 800, change: 2 },
    { id: 4, keyword: 'audit seo gratuit', position: 12, volume: 1500, change: 5 },
    { id: 5, keyword: 'optimisation seo', position: 7, volume: 950, change: -2 },
];

const recentIssues = [
    { id: 1, title: 'Balises title manquantes', affected_pages: 15, severity: 'error' },
    { id: 2, title: 'Meta descriptions courtes', affected_pages: 23, severity: 'warning' },
    { id: 3, title: 'Images sans alt', affected_pages: 42, severity: 'warning' },
];

const recommendations = [
    { id: 1, type: 'Technique', title: 'Optimiser les balises title', description: '15 pages à corriger', impact: 95 },
    { id: 2, type: 'Contenu', title: 'Enrichir le contenu', description: '8 pages minces détectées', impact: 75 },
];

onMounted(() => {
    if (visibilityChart.value) {
        const ctx = visibilityChart.value.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1 Jan', '5 Jan', '10 Jan', '15 Jan', '20 Jan', '25 Jan', '30 Jan'],
                datasets: [{
                    label: 'Visibilité (%)',
                    data: [32, 35, 38, 41, 43, 44, 45],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }
});

const getSeverityClass = (severity) => {
    return severity === 'error' ? 'bg-danger' : 'bg-warning';
};

const getSeverityIcon = (severity) => {
    return severity === 'error' ? 'fas fa-times-circle' : 'fas fa-exclamation-triangle';
};

const getTypeClass = (type) => {
    const classes = {
        'Technique': 'bg-primary',
        'Contenu': 'bg-warning',
        'Backlinks': 'bg-info',
    };
    return classes[type] || 'bg-secondary';
};

const editProject = () => {
    activeTab.value = 'settings';
};

const crawlNow = () => {
    window.$toast?.info('Analyse du site en cours...');
};

const updateProject = () => {
    window.$toast?.success('Projet mis à jour avec succès');
};

const deleteProject = () => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Supprimer ce projet ?',
            text: 'Cette action est irréversible',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/api/v1/projects/${props.project.id}`, {
                    onSuccess: () => {
                        router.visit('/projects');
                    },
                });
            }
        });
    }
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

.shadow-hover {
    transition: all 0.3s ease;
}

.shadow-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.icon-shape {
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.hover-bg {
    transition: background-color 0.2s ease;
}

.hover-bg:hover {
    background-color: rgba(99, 102, 241, 0.05);
}
</style>
