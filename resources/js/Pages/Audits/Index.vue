<template>
    <Head title="Audits SEO" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">Audits Techniques SEO</h1>
                    <p class="text-muted mb-0">Analysez et corrigez les problèmes techniques de vos sites</p>
                </div>
                <button class="btn btn-gradient-primary" @click="startNewAudit">
                    <i class="fas fa-play me-2"></i>
                    Lancer un audit
                </button>
            </div>

            <!-- SEO Score Card -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-3">Score SEO Global</h6>
                            <div class="position-relative d-inline-block mb-3">
                                <svg width="180" height="180" viewBox="0 0 180 180">
                                    <circle cx="90" cy="90" r="70" fill="none" stroke="#e9ecef" stroke-width="12"></circle>
                                    <circle
                                        cx="90"
                                        cy="90"
                                        r="70"
                                        fill="none"
                                        stroke="#10b981"
                                        stroke-width="12"
                                        stroke-dasharray="440"
                                        stroke-dashoffset="88"
                                        stroke-linecap="round"
                                        transform="rotate(-90 90 90)"
                                    ></circle>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div class="display-4 fw-bold text-success">{{ auditScore }}</div>
                                    <small class="text-muted">/100</small>
                                </div>
                            </div>
                            <p class="text-success fw-semibold mb-0">Très bon</p>
                            <small class="text-muted">Dernière analyse: {{ formatDate(lastAuditDate) }}</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-4">Répartition des problèmes</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center p-3 bg-danger bg-opacity-10 rounded">
                                        <div class="icon-shape bg-danger me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-times-circle text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-0 text-danger">{{ issues.errors }}</h4>
                                            <small class="text-muted">Erreurs critiques</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                        <div class="icon-shape bg-warning me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-0 text-warning">{{ issues.warnings }}</h4>
                                            <small class="text-muted">Avertissements</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                        <div class="icon-shape bg-info me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-info-circle text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-0 text-info">{{ issues.notices }}</h4>
                                            <small class="text-muted">Notices</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                        <div class="icon-shape bg-success me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="fw-bold mb-0 text-success">{{ issues.passed }}</h4>
                                            <small class="text-muted">Tests réussis</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Issues Tabs -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <ul class="nav nav-pills card-header-pills">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                :class="{ active: activeTab === 'all' }"
                                href="#"
                                @click.prevent="activeTab = 'all'"
                            >
                                Tous les problèmes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-danger"
                                :class="{ active: activeTab === 'errors' }"
                                href="#"
                                @click.prevent="activeTab = 'errors'"
                            >
                                <i class="fas fa-times-circle me-1"></i>
                                Erreurs ({{ issues.errors }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-warning"
                                :class="{ active: activeTab === 'warnings' }"
                                href="#"
                                @click.prevent="activeTab = 'warnings'"
                            >
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Avertissements ({{ issues.warnings }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-success"
                                :class="{ active: activeTab === 'passed' }"
                                href="#"
                                @click.prevent="activeTab = 'passed'"
                            >
                                <i class="fas fa-check-circle me-1"></i>
                                Réussis ({{ issues.passed }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div
                            v-for="(issue, index) in filteredIssues"
                            :key="issue.id"
                            class="list-group-item border-0 border-bottom hover-bg"
                            data-aos="fade-up"
                            :data-aos-delay="index * 50"
                        >
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div
                                        class="icon-shape"
                                        :class="getSeverityClass(issue.severity)"
                                        style="width: 40px; height: 40px;"
                                    >
                                        <i class="text-white" :class="getSeverityIcon(issue.severity)"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1 fw-semibold">{{ issue.title }}</h6>
                                        <span class="badge" :class="getSeverityBadgeClass(issue.severity)">
                                            {{ issue.severity }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-2">{{ issue.description }}</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <small class="text-muted">
                                            <i class="fas fa-file me-1"></i>
                                            {{ issue.affected_pages }} page(s) affectée(s)
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-chart-bar me-1"></i>
                                            Impact: {{ issue.impact }}
                                        </small>
                                    </div>
                                    <div v-if="issue.solution" class="mt-3 p-3 bg-light rounded">
                                        <strong class="small text-success">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            Solution:
                                        </strong>
                                        <p class="small mb-0 mt-1">{{ issue.solution }}</p>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <button class="btn btn-sm btn-outline-primary" @click="viewIssueDetails(issue)">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit History -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Historique des audits</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Projet</th>
                                    <th class="px-4 py-3 text-center">Score</th>
                                    <th class="px-4 py-3 text-center">Pages analysées</th>
                                    <th class="px-4 py-3 text-center">Problèmes</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="audit in auditHistory" :key="audit.id">
                                    <td class="px-4 py-3">{{ formatDate(audit.date) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-light text-dark">{{ audit.project }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="fw-bold" :class="getScoreColor(audit.score)">
                                                {{ audit.score }}
                                            </div>
                                            <small class="text-muted ms-1">/100</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ audit.pages_crawled }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-danger">{{ audit.issues }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge" :class="audit.status === 'completed' ? 'bg-success' : 'bg-warning'">
                                            {{ audit.status === 'completed' ? 'Terminé' : 'En cours' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" @click="viewAudit(audit)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" @click="downloadAudit(audit)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

const activeTab = ref('all');
const auditScore = 80;
const lastAuditDate = '2025-01-15';

const issues = {
    errors: 5,
    warnings: 12,
    notices: 8,
    passed: 45,
};

const mockIssues = [
    {
        id: 1,
        severity: 'error',
        title: 'Balises title manquantes',
        description: '15 pages n\'ont pas de balise title définie, ce qui nuit gravement au référencement.',
        affected_pages: 15,
        impact: 'Élevé',
        solution: 'Ajoutez une balise <title> unique et descriptive (50-60 caractères) dans le <head> de chaque page.',
    },
    {
        id: 2,
        severity: 'error',
        title: 'Erreurs 404',
        description: '8 liens internes pointent vers des pages inexistantes (erreur 404).',
        affected_pages: 8,
        impact: 'Élevé',
        solution: 'Corrigez les liens cassés ou créez des redirections 301 vers les pages appropriées.',
    },
    {
        id: 3,
        severity: 'warning',
        title: 'Meta descriptions trop courtes',
        description: '23 pages ont des meta descriptions de moins de 120 caractères.',
        affected_pages: 23,
        impact: 'Moyen',
        solution: 'Rédigez des meta descriptions entre 150-160 caractères pour optimiser le CTR.',
    },
    {
        id: 4,
        severity: 'warning',
        title: 'Images sans attribut alt',
        description: '42 images n\'ont pas d\'attribut alt défini, réduisant l\'accessibilité et le SEO.',
        affected_pages: 18,
        impact: 'Moyen',
        solution: 'Ajoutez un texte alternatif descriptif à chaque image avec l\'attribut alt.',
    },
    {
        id: 5,
        severity: 'passed',
        title: 'HTTPS activé',
        description: 'Toutes les pages utilisent le protocole HTTPS sécurisé.',
        affected_pages: 0,
        impact: 'Positif',
        solution: null,
    },
];

const auditHistory = [
    { id: 1, date: '2025-01-15', project: 'Site Principal', score: 80, pages_crawled: 127, issues: 25, status: 'completed' },
    { id: 2, date: '2025-01-01', project: 'Site Principal', score: 75, pages_crawled: 123, issues: 32, status: 'completed' },
    { id: 3, date: '2024-12-15', project: 'Blog', score: 82, pages_crawled: 89, issues: 18, status: 'completed' },
];

const filteredIssues = computed(() => {
    if (activeTab.value === 'all') return mockIssues;
    if (activeTab.value === 'errors') return mockIssues.filter(i => i.severity === 'error');
    if (activeTab.value === 'warnings') return mockIssues.filter(i => i.severity === 'warning');
    if (activeTab.value === 'passed') return mockIssues.filter(i => i.severity === 'passed');
    return mockIssues;
});

const getSeverityClass = (severity) => {
    const classes = {
        error: 'bg-danger',
        warning: 'bg-warning',
        notice: 'bg-info',
        passed: 'bg-success',
    };
    return classes[severity] || 'bg-secondary';
};

const getSeverityIcon = (severity) => {
    const icons = {
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        notice: 'fas fa-info-circle',
        passed: 'fas fa-check-circle',
    };
    return icons[severity] || 'fas fa-circle';
};

const getSeverityBadgeClass = (severity) => {
    const classes = {
        error: 'bg-danger',
        warning: 'bg-warning',
        notice: 'bg-info',
        passed: 'bg-success',
    };
    return classes[severity] || 'bg-secondary';
};

const getScoreColor = (score) => {
    if (score >= 80) return 'text-success';
    if (score >= 60) return 'text-warning';
    return 'text-danger';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const startNewAudit = () => {
    window.$toast?.info('Lancement d\'un nouvel audit...');
};

const viewIssueDetails = (issue) => {
    window.$toast?.info(`Détails de: ${issue.title}`);
};

const viewAudit = (audit) => {
    window.$toast?.info(`Visualisation de l'audit du ${formatDate(audit.date)}`);
};

const downloadAudit = (audit) => {
    window.$toast?.success(`Téléchargement de l'audit du ${formatDate(audit.date)}`);
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

.icon-shape {
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
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
