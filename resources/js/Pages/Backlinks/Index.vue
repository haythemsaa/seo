<template>
    <Head title="Backlinks" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">Analyse des Backlinks</h1>
                    <p class="text-muted mb-0">Suivez et analysez vos liens entrants</p>
                </div>
                <button class="btn btn-gradient-primary" @click="checkBacklinks">
                    <i class="fas fa-sync me-2"></i>
                    Vérifier les backlinks
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-primary me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-link text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Total backlinks</p>
                                    <h3 class="fw-bold mb-0">{{ stats.total }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        +{{ stats.new }} ce mois
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
                                    <i class="fas fa-globe text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Domaines référents</p>
                                    <h3 class="fw-bold mb-0">{{ stats.domains }}</h3>
                                    <small class="text-muted">Uniques</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-warning me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Domain Authority (moy.)</p>
                                    <h3 class="fw-bold mb-0">{{ stats.avgDA }}</h3>
                                    <small class="text-muted">/100</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-danger me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Backlinks perdus</p>
                                    <h3 class="fw-bold mb-0 text-danger">{{ stats.lost }}</h3>
                                    <small class="text-muted">Ce mois</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                :class="{ active: activeFilter === 'all' }"
                                href="#"
                                @click.prevent="activeFilter = 'all'"
                            >
                                Tous ({{ mockBacklinks.length }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                :class="{ active: activeFilter === 'dofollow' }"
                                href="#"
                                @click.prevent="activeFilter = 'dofollow'"
                            >
                                DoFollow ({{ getCountByType('dofollow') }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                :class="{ active: activeFilter === 'nofollow' }"
                                href="#"
                                @click.prevent="activeFilter = 'nofollow'"
                            >
                                NoFollow ({{ getCountByType('nofollow') }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-danger"
                                :class="{ active: activeFilter === 'lost' }"
                                href="#"
                                @click.prevent="activeFilter = 'lost'"
                            >
                                <i class="fas fa-unlink me-1"></i>
                                Perdus ({{ getCountByStatus('lost') }})
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Backlinks Table -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold">Source</th>
                                    <th class="px-4 py-3 fw-semibold">Page cible</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Type</th>
                                    <th class="px-4 py-3 fw-semibold text-center">DA</th>
                                    <th class="px-4 py-3 fw-semibold text-center">PA</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Statut</th>
                                    <th class="px-4 py-3 fw-semibold">Découvert</th>
                                    <th class="px-4 py-3 fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredBacklinks.length === 0">
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-link fs-2 mb-3 d-block"></i>
                                        Aucun backlink trouvé
                                    </td>
                                </tr>
                                <tr v-for="backlink in filteredBacklinks" :key="backlink.id">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-start">
                                            <img
                                                :src="`https://www.google.com/s2/favicons?domain=${getDomain(backlink.source_url)}&sz=32`"
                                                class="me-2 rounded"
                                                style="width: 24px; height: 24px;"
                                                alt="favicon"
                                            />
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold small">{{ getDomain(backlink.source_url) }}</div>
                                                <a :href="backlink.source_url" target="_blank" class="text-primary small text-decoration-none">
                                                    {{ truncateUrl(backlink.source_url) }}
                                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a :href="backlink.target_url" target="_blank" class="text-dark text-decoration-none small">
                                            {{ truncateUrl(backlink.target_url, 40) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="badge"
                                            :class="backlink.link_type === 'dofollow' ? 'bg-success' : 'bg-secondary'"
                                        >
                                            {{ backlink.link_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="progress mb-1" style="width: 60px; height: 6px;">
                                                <div
                                                    class="progress-bar"
                                                    :class="getDAColor(backlink.domain_authority)"
                                                    :style="{ width: backlink.domain_authority + '%' }"
                                                ></div>
                                            </div>
                                            <small class="fw-semibold">{{ backlink.domain_authority }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="progress mb-1" style="width: 60px; height: 6px;">
                                                <div
                                                    class="progress-bar"
                                                    :class="getPAColor(backlink.page_authority)"
                                                    :style="{ width: backlink.page_authority + '%' }"
                                                ></div>
                                            </div>
                                            <small class="fw-semibold">{{ backlink.page_authority }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="badge rounded-pill"
                                            :class="getStatusClass(backlink.status)"
                                        >
                                            {{ getStatusLabel(backlink.status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <small class="text-muted">{{ formatDate(backlink.first_seen_at) }}</small>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir détails" @click="viewBacklink(backlink)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" title="Analyser" @click="analyzeBacklink(backlink)">
                                                <i class="fas fa-chart-bar"></i>
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

const activeFilter = ref('all');

// Mock data
const mockBacklinks = [
    {
        id: 1,
        source_url: 'https://blog.example.com/article-seo',
        target_url: 'https://monsite.fr/services',
        link_type: 'dofollow',
        domain_authority: 65,
        page_authority: 58,
        status: 'active',
        first_seen_at: '2025-01-10',
    },
    {
        id: 2,
        source_url: 'https://forum.marketing.fr/discussion/123',
        target_url: 'https://monsite.fr/',
        link_type: 'nofollow',
        domain_authority: 42,
        page_authority: 35,
        status: 'active',
        first_seen_at: '2025-01-08',
    },
    {
        id: 3,
        source_url: 'https://annuaire-pro.com/entreprise/456',
        target_url: 'https://monsite.fr/contact',
        link_type: 'dofollow',
        domain_authority: 38,
        page_authority: 40,
        status: 'lost',
        first_seen_at: '2024-12-15',
    },
    {
        id: 4,
        source_url: 'https://magazine-digital.fr/interview',
        target_url: 'https://monsite.fr/a-propos',
        link_type: 'dofollow',
        domain_authority: 72,
        page_authority: 68,
        status: 'active',
        first_seen_at: '2025-01-05',
    },
    {
        id: 5,
        source_url: 'https://partenaire.com/ressources',
        target_url: 'https://monsite.fr/blog',
        link_type: 'dofollow',
        domain_authority: 55,
        page_authority: 52,
        status: 'active',
        first_seen_at: '2024-12-28',
    },
];

const stats = computed(() => ({
    total: mockBacklinks.length,
    new: mockBacklinks.filter(b => new Date(b.first_seen_at) > new Date('2025-01-01')).length,
    domains: new Set(mockBacklinks.map(b => getDomain(b.source_url))).size,
    avgDA: Math.round(mockBacklinks.reduce((sum, b) => sum + b.domain_authority, 0) / mockBacklinks.length),
    lost: mockBacklinks.filter(b => b.status === 'lost').length,
}));

const filteredBacklinks = computed(() => {
    let links = mockBacklinks;

    if (activeFilter.value === 'dofollow') {
        links = links.filter(b => b.link_type === 'dofollow');
    } else if (activeFilter.value === 'nofollow') {
        links = links.filter(b => b.link_type === 'nofollow');
    } else if (activeFilter.value === 'lost') {
        links = links.filter(b => b.status === 'lost');
    }

    return links;
});

const getCountByType = (type) => {
    return mockBacklinks.filter(b => b.link_type === type).length;
};

const getCountByStatus = (status) => {
    return mockBacklinks.filter(b => b.status === status).length;
};

const getDomain = (url) => {
    try {
        return new URL(url).hostname.replace('www.', '');
    } catch {
        return url;
    }
};

const truncateUrl = (url, maxLength = 50) => {
    if (url.length <= maxLength) return url;
    return url.substring(0, maxLength) + '...';
};

const getDAColor = (da) => {
    if (da >= 60) return 'bg-success';
    if (da >= 40) return 'bg-warning';
    return 'bg-danger';
};

const getPAColor = (pa) => {
    if (pa >= 60) return 'bg-success';
    if (pa >= 40) return 'bg-info';
    return 'bg-secondary';
};

const getStatusClass = (status) => {
    return status === 'active' ? 'bg-success' : 'bg-danger';
};

const getStatusLabel = (status) => {
    return status === 'active' ? 'Actif' : 'Perdu';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const checkBacklinks = () => {
    window.$toast?.info('Vérification des backlinks en cours...');
};

const viewBacklink = (backlink) => {
    window.$toast?.info(`Détails du backlink de ${getDomain(backlink.source_url)}`);
};

const analyzeBacklink = (backlink) => {
    window.$toast?.info(`Analyse du backlink en cours...`);
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

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.05);
}
</style>
