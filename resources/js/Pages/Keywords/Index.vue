<template>
    <Head title="Mots-clés" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">Suivi des Mots-clés</h1>
                    <p class="text-muted mb-0">Analysez les positions de vos mots-clés sur les moteurs de recherche</p>
                </div>
                <button @click="showAddModal = true" class="btn btn-gradient-primary">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter des mots-clés
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-hover h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-primary me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-key text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Total mots-clés</p>
                                    <h3 class="fw-bold mb-0">{{ stats.total || 0 }}</h3>
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
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Top 3</p>
                                    <h3 class="fw-bold mb-0 text-success">{{ stats.top3 || 0 }}</h3>
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
                                    <p class="text-muted small mb-1">Top 10</p>
                                    <h3 class="fw-bold mb-0 text-warning">{{ stats.top10 || 0 }}</h3>
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
                                    <h3 class="fw-bold mb-0 text-info">{{ stats.avgPosition || 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input
                                    v-model="search"
                                    type="text"
                                    class="form-control"
                                    placeholder="Rechercher un mot-clé..."
                                />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select v-model="filterProject" class="form-select">
                                <option value="">Tous les projets</option>
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select v-model="filterPosition" class="form-select">
                                <option value="">Toutes positions</option>
                                <option value="top3">Top 3</option>
                                <option value="top10">Top 10</option>
                                <option value="top20">Top 20</option>
                                <option value="other">20+</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" @click="resetFilters">
                                <i class="fas fa-redo me-2"></i>
                                Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keywords Table -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold">Mot-clé</th>
                                    <th class="px-4 py-3 fw-semibold">Projet</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Position actuelle</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Évolution</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Volume</th>
                                    <th class="px-4 py-3 fw-semibold text-center">Difficulté</th>
                                    <th class="px-4 py-3 fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredKeywords.length === 0">
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-search fs-2 mb-3 d-block"></i>
                                        Aucun mot-clé trouvé
                                    </td>
                                </tr>
                                <tr v-for="keyword in filteredKeywords" :key="keyword.id" class="cursor-pointer" @click="viewKeyword(keyword)">
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold">{{ keyword.keyword }}</div>
                                        <small class="text-muted">{{ keyword.search_intent }}</small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-light text-dark">{{ keyword.project?.name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="badge rounded-pill px-3 py-2"
                                            :class="getPositionClass(keyword.current_position)"
                                        >
                                            {{ keyword.current_position || '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span v-if="keyword.position_change" :class="keyword.position_change > 0 ? 'text-success' : 'text-danger'">
                                            <i :class="keyword.position_change > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                                            {{ Math.abs(keyword.position_change) }}
                                        </span>
                                        <span v-else class="text-muted">-</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ formatNumber(keyword.search_volume) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="progress" style="height: 6px;">
                                            <div
                                                class="progress-bar"
                                                :class="getDifficultyColor(keyword.difficulty)"
                                                :style="{ width: keyword.difficulty + '%' }"
                                            ></div>
                                        </div>
                                        <small class="text-muted">{{ keyword.difficulty }}/100</small>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="btn-group btn-group-sm" @click.stop>
                                            <button class="btn btn-outline-primary" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" @click="deleteKeyword(keyword)" title="Supprimer">
                                                <i class="fas fa-trash"></i>
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

        <!-- Add Keywords Modal -->
        <Teleport to="body">
            <div v-if="showAddModal" class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-plus-circle text-primary me-2"></i>
                                Ajouter des mots-clés
                            </h5>
                            <button type="button" class="btn-close" @click="showAddModal = false"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Projet</label>
                                <select v-model="addForm.project_id" class="form-select" required>
                                    <option value="">Sélectionner un projet</option>
                                    <option v-for="project in projects" :key="project.id" :value="project.id">
                                        {{ project.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mots-clés (un par ligne)</label>
                                <textarea
                                    v-model="addForm.keywords"
                                    class="form-control"
                                    rows="8"
                                    placeholder="agence seo&#10;référencement naturel&#10;consultant seo"
                                    required
                                ></textarea>
                                <small class="text-muted">Entrez un mot-clé par ligne</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" @click="showAddModal = false">Annuler</button>
                            <button type="button" class="btn btn-gradient-primary" @click="addKeywords">
                                <i class="fas fa-check me-2"></i>
                                Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    keywords: {
        type: Array,
        default: () => []
    },
    projects: {
        type: Array,
        default: () => []
    },
});

const search = ref('');
const filterProject = ref('');
const filterPosition = ref('');
const showAddModal = ref(false);

const addForm = ref({
    project_id: '',
    keywords: '',
});

// Mock data for demonstration
const mockKeywords = [
    { id: 1, keyword: 'agence seo paris', project: { name: 'Site Principal' }, current_position: 2, position_change: 3, search_volume: 1200, difficulty: 65, search_intent: 'Commercial' },
    { id: 2, keyword: 'référencement naturel', project: { name: 'Site Principal' }, current_position: 8, position_change: -2, search_volume: 2500, difficulty: 72, search_intent: 'Informational' },
    { id: 3, keyword: 'consultant seo', project: { name: 'Blog' }, current_position: 15, position_change: 5, search_volume: 800, difficulty: 58, search_intent: 'Commercial' },
];

const stats = computed(() => {
    const keywords = props.keywords.length > 0 ? props.keywords : mockKeywords;
    return {
        total: keywords.length,
        top3: keywords.filter(k => k.current_position <= 3).length,
        top10: keywords.filter(k => k.current_position <= 10).length,
        avgPosition: keywords.length > 0
            ? Math.round(keywords.reduce((sum, k) => sum + (k.current_position || 0), 0) / keywords.length)
            : 0,
    };
});

const filteredKeywords = computed(() => {
    let keywords = props.keywords.length > 0 ? props.keywords : mockKeywords;

    if (search.value) {
        keywords = keywords.filter(k =>
            k.keyword.toLowerCase().includes(search.value.toLowerCase())
        );
    }

    if (filterProject.value) {
        keywords = keywords.filter(k => k.project?.id === filterProject.value);
    }

    if (filterPosition.value) {
        keywords = keywords.filter(k => {
            const pos = k.current_position;
            if (filterPosition.value === 'top3') return pos <= 3;
            if (filterPosition.value === 'top10') return pos <= 10;
            if (filterPosition.value === 'top20') return pos <= 20;
            if (filterPosition.value === 'other') return pos > 20;
            return true;
        });
    }

    return keywords;
});

const getPositionClass = (position) => {
    if (!position) return 'bg-secondary';
    if (position <= 3) return 'bg-success';
    if (position <= 10) return 'bg-warning';
    if (position <= 20) return 'bg-info';
    return 'bg-secondary';
};

const getDifficultyColor = (difficulty) => {
    if (difficulty <= 33) return 'bg-success';
    if (difficulty <= 66) return 'bg-warning';
    return 'bg-danger';
};

const formatNumber = (num) => {
    if (!num) return '-';
    return num.toLocaleString('fr-FR');
};

const resetFilters = () => {
    search.value = '';
    filterProject.value = '';
    filterPosition.value = '';
};

const viewKeyword = (keyword) => {
    window.$toast?.info(`Détails de "${keyword.keyword}" (à implémenter)`);
};

const addKeywords = () => {
    if (!addForm.value.project_id || !addForm.value.keywords) {
        window.$toast?.error('Veuillez remplir tous les champs');
        return;
    }

    window.$toast?.success('Mots-clés ajoutés avec succès!');
    showAddModal.value = false;
    addForm.value = { project_id: '', keywords: '' };
};

const deleteKeyword = (keyword) => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Supprimer ce mot-clé ?',
            text: `"${keyword.keyword}" sera supprimé définitivement`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                window.$toast?.success('Mot-clé supprimé');
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

.btn-gradient-primary:hover:not(:disabled) {
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

.cursor-pointer {
    cursor: pointer;
}

tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.05);
}
</style>
