<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="container-fluid py-4">
            <!-- Page Header -->
            <div class="row mb-4" data-aos="fade-down">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="display-5 fw-bold text-gradient mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                Dashboard
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ currentDate }}
                            </p>
                        </div>
                        <div>
                            <button class="btn btn-gradient-primary" @click="refreshData">
                                <i class="fas fa-sync-alt me-2"></i>
                                Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" v-for="(stat, index) in stats" :key="index"
                     :data-aos="'fade-up'" :data-aos-delay="index * 100">
                    <div class="card card-stats shadow-hover h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <span class="text-muted text-uppercase small fw-semibold">{{ stat.label }}</span>
                                    <h2 class="mb-0 mt-2 fw-bold">{{ stat.value }}</h2>
                                    <div class="mt-2">
                                        <span :class="['badge', stat.change >= 0 ? 'bg-success' : 'bg-danger']">
                                            <i :class="['fas', stat.change >= 0 ? 'fa-arrow-up' : 'fa-arrow-down', 'me-1']"></i>
                                            {{ Math.abs(stat.change) }}%
                                        </span>
                                        <span class="text-muted small ms-2">vs dernier mois</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div :class="['icon-shape', stat.colorClass]">
                                        <i :class="['fas', stat.icon, 'text-white']"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <!-- Visibility Chart -->
                <div class="col-xl-8 col-lg-7" data-aos="fade-right">
                    <div class="card shadow-soft h-100">
                        <div class="card-header bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-area text-primary me-2"></i>
                                    Évolution de la visibilité
                                </h5>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button v-for="period in periods" :key="period"
                                            :class="['btn', selectedPeriod === period ? 'btn-primary' : 'btn-outline-primary']"
                                            @click="selectedPeriod = period">
                                        {{ period }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas ref="visibilityChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Rankings Distribution -->
                <div class="col-xl-4 col-lg-5" data-aos="fade-left">
                    <div class="card shadow-soft h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-chart-pie text-success me-2"></i>
                                Distribution des positions
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas ref="distributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Top Keywords -->
            <div class="row g-4">
                <!-- Top Performing Keywords -->
                <div class="col-xl-6" data-aos="fade-up">
                    <div class="card shadow-soft">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-trophy text-warning me-2"></i>
                                Top mots-clés performants
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Mot-clé</th>
                                            <th>Position</th>
                                            <th>Évolution</th>
                                            <th>Volume</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="keyword in topKeywords" :key="keyword.id">
                                            <td class="fw-semibold">{{ keyword.keyword }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ keyword.position }}</span>
                                            </td>
                                            <td>
                                                <span :class="['badge', keyword.change > 0 ? 'bg-success' : 'bg-danger']">
                                                    <i :class="['fas', keyword.change > 0 ? 'fa-arrow-up' : 'fa-arrow-down']"></i>
                                                    {{ Math.abs(keyword.change) }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ keyword.volume }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Recommendations -->
                <div class="col-xl-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card shadow-soft">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-lightbulb text-info me-2"></i>
                                Recommandations IA récentes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div v-for="(rec, index) in recommendations" :key="index"
                                 class="d-flex align-items-start mb-3 pb-3"
                                 :class="{'border-bottom': index < recommendations.length - 1}">
                                <div :class="['icon-shape icon-shape-sm', getPriorityColor(rec.priority), 'me-3']">
                                    <i class="fas fa-exclamation text-white small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ rec.title }}</h6>
                                    <p class="text-muted small mb-2">{{ rec.description }}</p>
                                    <div class="d-flex align-items-center">
                                        <span :class="['badge', 'badge-sm', getPriorityBadge(rec.priority), 'me-2']">
                                            {{ rec.priority }}
                                        </span>
                                        <span class="text-muted small">
                                            Impact: <span class="fw-semibold">{{ rec.impact }}</span>
                                        </span>
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
import { ref, onMounted, computed, getCurrentInstance } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Chart } from 'chart.js';

const { proxy } = getCurrentInstance();

const selectedPeriod = ref('7J');
const periods = ['7J', '30J', '90J'];

const visibilityChart = ref(null);
const distributionChart = ref(null);

const stats = ref([
    { label: 'Projets Actifs', value: '12', change: 15.5, icon: 'fa-folder-open', colorClass: 'bg-gradient-primary' },
    { label: 'Mots-clés suivis', value: '1,245', change: 8.2, icon: 'fa-key', colorClass: 'bg-gradient-success' },
    { label: 'Backlinks actifs', value: '3,567', change: 12.4, icon: 'fa-link', colorClass: 'bg-gradient-warning' },
    { label: 'Score moyen', value: '78%', change: -2.1, icon: 'fa-chart-bar', colorClass: 'bg-gradient-info' },
]);

const topKeywords = ref([
    { id: 1, keyword: 'agence seo paris', position: 2, change: 3, volume: '2.4K' },
    { id: 2, keyword: 'référencement naturel', position: 4, change: 1, volume: '1.8K' },
    { id: 3, keyword: 'audit seo', position: 5, change: -2, volume: '1.2K' },
    { id: 4, keyword: 'consultant seo', position: 7, change: 5, volume: '980' },
    { id: 5, keyword: 'stratégie seo', position: 3, change: 2, volume: '750' },
]);

const recommendations = ref([
    {
        title: 'Optimiser les balises title',
        description: '45 pages ont des titles trop longs',
        priority: 'Critique',
        impact: '95/100'
    },
    {
        title: 'Améliorer la vitesse de chargement',
        description: 'Le LCP moyen est de 3.2s',
        priority: 'Élevé',
        impact: '85/100'
    },
    {
        title: 'Développer le profil de backlinks',
        description: 'Seulement 234 domaines référents',
        priority: 'Moyen',
        impact: '70/100'
    },
]);

const currentDate = computed(() => {
    return new Date().toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

const getPriorityColor = (priority) => {
    const colors = {
        'Critique': 'bg-gradient-danger',
        'Élevé': 'bg-gradient-warning',
        'Moyen': 'bg-gradient-info',
        'Faible': 'bg-gradient-success'
    };
    return colors[priority] || 'bg-gradient-secondary';
};

const getPriorityBadge = (priority) => {
    const badges = {
        'Critique': 'bg-danger',
        'Élevé': 'bg-warning',
        'Moyen': 'bg-info',
        'Faible': 'bg-success'
    };
    return badges[priority] || 'bg-secondary';
};

const refreshData = () => {
    proxy.$toast.info('Actualisation des données...');
    // Logic to refresh data
    setTimeout(() => {
        proxy.$toast.success('Données actualisées avec succès !');
    }, 1000);
};

onMounted(() => {
    // Visibility Chart
    const ctxVisibility = visibilityChart.value.getContext('2d');
    new Chart(ctxVisibility, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Score de visibilité',
                data: [65, 68, 70, 72, 71, 75, 78],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#6366f1',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: (context) => `Score: ${context.parsed.y}%`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                    },
                    ticks: {
                        callback: (value) => value + '%'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Distribution Chart
    const ctxDistribution = distributionChart.value.getContext('2d');
    new Chart(ctxDistribution, {
        type: 'doughnut',
        data: {
            labels: ['Top 3', 'Top 10', 'Top 20', 'Top 50', '50+'],
            datasets: [{
                data: [125, 280, 195, 180, 465],
                backgroundColor: [
                    '#10b981',
                    '#06b6d4',
                    '#f59e0b',
                    '#ef4444',
                    '#6b7280'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: (context) => {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} mots-clés (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
});
</script>

<style scoped>
.icon-shape-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}
</style>
