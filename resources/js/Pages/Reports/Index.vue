<template>
    <Head title="Rapports" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">Rapports & Analyses</h1>
                    <p class="text-muted mb-0">Générez et exportez vos rapports SEO personnalisés</p>
                </div>
                <button class="btn btn-gradient-primary" @click="showGenerateModal = true">
                    <i class="fas fa-file-pdf me-2"></i>
                    Nouveau Rapport
                </button>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-primary me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Trafic organique</p>
                                    <h3 class="fw-bold mb-0">{{ stats.traffic.toLocaleString() }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        +{{ stats.trafficGrowth }}%
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
                                    <i class="fas fa-mouse-pointer text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Taux de clics (CTR)</p>
                                    <h3 class="fw-bold mb-0">{{ stats.ctr }}%</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        +{{ stats.ctrGrowth }}%
                                    </small>
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
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Impressions</p>
                                    <h3 class="fw-bold mb-0">{{ (stats.impressions / 1000).toFixed(1) }}k</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        +{{ stats.impressionsGrowth }}%
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
                                <div class="icon-shape bg-gradient-info me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-trophy text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Position moyenne</p>
                                    <h3 class="fw-bold mb-0">{{ stats.avgPosition }}</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        -{{ stats.positionImprovement }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Performance SEO (30 derniers jours)</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active">7J</button>
                            <button type="button" class="btn btn-outline-primary">30J</button>
                            <button type="button" class="btn btn-outline-primary">90J</button>
                            <button type="button" class="btn btn-outline-primary">1A</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas ref="performanceChart" height="80"></canvas>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="row g-4 mb-4">
                <!-- Top Keywords -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-star text-warning me-2"></i>
                                Top 10 Mots-clés
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3">Mot-clé</th>
                                            <th class="px-4 py-3 text-center">Position</th>
                                            <th class="px-4 py-3 text-center">Clics</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="keyword in topKeywords" :key="keyword.id">
                                            <td class="px-4 py-3">
                                                <div class="fw-semibold">{{ keyword.keyword }}</div>
                                                <small class="text-muted">{{ keyword.volume.toLocaleString() }} vol.</small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-success">{{ keyword.position }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <strong>{{ keyword.clicks }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Pages -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-file-alt text-info me-2"></i>
                                Pages les plus performantes
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-4 py-3">Page</th>
                                            <th class="px-4 py-3 text-center">Clics</th>
                                            <th class="px-4 py-3 text-center">CTR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="page in topPages" :key="page.url">
                                            <td class="px-4 py-3">
                                                <div class="small fw-semibold">{{ page.title }}</div>
                                                <small class="text-muted">{{ truncateUrl(page.url) }}</small>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <strong>{{ page.clicks }}</strong>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="badge bg-info">{{ page.ctr }}%</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generated Reports List -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Rapports générés</h5>
                </div>
                <div class="card-body">
                    <div v-if="reports.length === 0" class="text-center py-5 text-muted">
                        <i class="fas fa-file-pdf fs-1 mb-3 d-block"></i>
                        Aucun rapport généré pour le moment
                    </div>
                    <div v-else class="list-group list-group-flush">
                        <div
                            v-for="report in reports"
                            :key="report.id"
                            class="list-group-item px-0 border-0 border-bottom hover-bg"
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-gradient-danger me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-file-pdf text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">{{ report.title }}</h6>
                                        <small class="text-muted">
                                            {{ report.project }} • {{ formatDate(report.created_at) }}
                                            <span class="badge bg-light text-dark ms-2">{{ report.type }}</span>
                                        </small>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" @click="downloadReport(report)">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </button>
                                    <button class="btn btn-outline-secondary" @click="viewReport(report)">
                                        <i class="fas fa-eye me-1"></i>
                                        Aperçu
                                    </button>
                                    <button class="btn btn-outline-danger" @click="deleteReport(report)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Report Modal -->
        <Teleport to="body">
            <div v-if="showGenerateModal" class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                Générer un rapport
                            </h5>
                            <button type="button" class="btn-close" @click="showGenerateModal = false"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Type de rapport</label>
                                <select v-model="reportForm.type" class="form-select">
                                    <option value="monthly">Rapport mensuel</option>
                                    <option value="quarterly">Rapport trimestriel</option>
                                    <option value="custom">Personnalisé</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Projet</label>
                                <select v-model="reportForm.project_id" class="form-select">
                                    <option value="">Tous les projets</option>
                                    <option value="1">Site Principal</option>
                                    <option value="2">Blog</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Période</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" v-model="reportForm.start_date" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <input type="date" v-model="reportForm.end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sections à inclure</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="reportForm.include_keywords" id="include_keywords">
                                    <label class="form-check-label" for="include_keywords">Mots-clés</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="reportForm.include_backlinks" id="include_backlinks">
                                    <label class="form-check-label" for="include_backlinks">Backlinks</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="reportForm.include_technical" id="include_technical">
                                    <label class="form-check-label" for="include_technical">Audit technique</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-model="reportForm.include_recommendations" id="include_recommendations">
                                    <label class="form-check-label" for="include_recommendations">Recommandations</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" @click="showGenerateModal = false">Annuler</button>
                            <button type="button" class="btn btn-gradient-primary" @click="generateReport">
                                <i class="fas fa-cog me-2"></i>
                                Générer le rapport
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Chart } from 'chart.js';

const showGenerateModal = ref(false);

const stats = {
    traffic: 12543,
    trafficGrowth: 23.5,
    ctr: 4.2,
    ctrGrowth: 12.3,
    impressions: 45600,
    impressionsGrowth: 18.7,
    avgPosition: 8.5,
    positionImprovement: 2.3,
};

const topKeywords = [
    { id: 1, keyword: 'agence seo paris', position: 2, clicks: 245, volume: 1200 },
    { id: 2, keyword: 'référencement naturel', position: 5, clicks: 189, volume: 2500 },
    { id: 3, keyword: 'consultant seo', position: 3, clicks: 156, volume: 800 },
    { id: 4, keyword: 'audit seo gratuit', position: 7, clicks: 134, volume: 1500 },
    { id: 5, keyword: 'optimisation seo', position: 4, clicks: 98, volume: 950 },
];

const topPages = [
    { url: '/services/referencement', title: 'Services de référencement', clicks: 456, ctr: 5.2 },
    { url: '/blog/guide-seo-2025', title: 'Guide SEO 2025', clicks: 389, ctr: 4.8 },
    { url: '/contact', title: 'Nous contacter', clicks: 267, ctr: 3.9 },
    { url: '/a-propos', title: 'À propos', clicks: 198, ctr: 3.2 },
    { url: '/tarifs', title: 'Nos tarifs', clicks: 176, ctr: 4.1 },
];

const reports = [
    { id: 1, title: 'Rapport mensuel - Janvier 2025', project: 'Site Principal', type: 'Mensuel', created_at: '2025-01-15' },
    { id: 2, title: 'Rapport mensuel - Décembre 2024', project: 'Site Principal', type: 'Mensuel', created_at: '2024-12-15' },
];

const reportForm = ref({
    type: 'monthly',
    project_id: '',
    start_date: '',
    end_date: '',
    include_keywords: true,
    include_backlinks: true,
    include_technical: true,
    include_recommendations: true,
});

const performanceChart = ref(null);

onMounted(() => {
    if (performanceChart.value) {
        const ctx = performanceChart.value.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1 Jan', '5 Jan', '10 Jan', '15 Jan', '20 Jan', '25 Jan', '30 Jan'],
                datasets: [
                    {
                        label: 'Clics',
                        data: [320, 380, 410, 450, 480, 520, 560],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Impressions',
                        data: [8500, 9200, 9800, 10500, 11200, 11800, 12400],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                },
            },
        });
    }
});

const truncateUrl = (url, maxLength = 40) => {
    if (url.length <= maxLength) return url;
    return url.substring(0, maxLength) + '...';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const generateReport = () => {
    window.$toast?.success('Génération du rapport en cours...');
    showGenerateModal.value = false;
};

const downloadReport = (report) => {
    window.$toast?.success(`Téléchargement de "${report.title}"`);
};

const viewReport = (report) => {
    window.$toast?.info(`Aperçu de "${report.title}"`);
};

const deleteReport = (report) => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Supprimer ce rapport ?',
            text: `"${report.title}" sera supprimé définitivement`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                window.$toast?.success('Rapport supprimé');
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

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.hover-bg {
    transition: background-color 0.2s ease;
}

.hover-bg:hover {
    background-color: rgba(99, 102, 241, 0.05);
}
</style>
