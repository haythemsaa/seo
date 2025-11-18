<template>
    <Head title="Projets" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
                <div>
                    <h1 class="h2 fw-bold mb-1">Mes Projets</h1>
                    <p class="text-muted mb-0">Gérez et suivez tous vos projets SEO</p>
                </div>
                <button @click="showCreateModal = true" class="btn btn-gradient-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nouveau Projet
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="projects.length === 0" class="card border-0 shadow-sm text-center" style="padding: 5rem 2rem;" data-aos="fade-up">
                <div class="card-body">
                    <div class="icon-shape bg-gradient-primary mb-4 mx-auto" style="width: 80px; height: 80px;">
                        <i class="fas fa-folder-open text-white fs-2"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Aucun projet</h3>
                    <p class="text-muted mb-4">Créez votre premier projet pour commencer à suivre votre référencement</p>
                    <button @click="showCreateModal = true" class="btn btn-gradient-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>
                        Créer mon premier projet
                    </button>
                </div>
            </div>

            <!-- Projects Grid -->
            <div v-else class="row g-4">
                <div
                    v-for="(project, index) in projects"
                    :key="project.id"
                    class="col-md-6 col-lg-4"
                    data-aos="fade-up"
                    :data-aos-delay="index * 100"
                >
                    <div class="card h-100 border-0 shadow-hover cursor-pointer" @click="openProject(project.id)">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold mb-1">{{ project.name }}</h5>
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-globe me-1"></i>
                                        {{ project.website_url }}
                                    </p>
                                </div>
                                <span
                                    class="badge rounded-pill ms-2"
                                    :class="project.is_active ? 'bg-success' : 'bg-secondary'"
                                >
                                    {{ project.is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <div class="fw-bold text-primary h5 mb-0">{{ project.keywords_count || 0 }}</div>
                                        <small class="text-muted">Mots-clés</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <div class="fw-bold text-info h5 mb-0">{{ project.backlinks_count || 0 }}</div>
                                        <small class="text-muted">Backlinks</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center p-2 bg-light rounded">
                                        <div class="fw-bold text-success h5 mb-0">{{ project.visibility_score || 0 }}%</div>
                                        <small class="text-muted">Visibilité</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ formatDate(project.last_crawled_at) }}
                                </small>
                                <div class="btn-group btn-group-sm" role="group" @click.stop>
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary"
                                        @click="openProject(project.id)"
                                        title="Voir le projet"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        @click="editProject(project)"
                                        title="Modifier"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger"
                                        @click="deleteProject(project)"
                                        title="Supprimer"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Project Modal -->
        <Teleport to="body">
            <div v-if="showCreateModal" class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg" data-aos="zoom-in" data-aos-duration="300">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">
                                <i class="fas fa-folder-plus text-primary me-2"></i>
                                Créer un nouveau projet
                            </h5>
                            <button type="button" class="btn-close" @click="showCreateModal = false"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <form @submit.prevent="createProject">
                                <div class="mb-3">
                                    <label for="project_name" class="form-label fw-semibold">Nom du projet</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-tag text-muted"></i>
                                        </span>
                                        <input
                                            id="project_name"
                                            v-model="createForm.name"
                                            type="text"
                                            class="form-control"
                                            placeholder="Mon site e-commerce"
                                            required
                                        />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="website_url" class="form-label fw-semibold">URL du site</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-globe text-muted"></i>
                                        </span>
                                        <input
                                            id="website_url"
                                            v-model="createForm.website_url"
                                            type="url"
                                            class="form-control"
                                            placeholder="https://example.com"
                                            required
                                        />
                                    </div>
                                    <small class="text-muted">L'URL complète de votre site web</small>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="country_code" class="form-label fw-semibold">Pays cible</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-flag text-muted"></i>
                                            </span>
                                            <select id="country_code" v-model="createForm.country_code" class="form-select">
                                                <option value="FR">🇫🇷 France</option>
                                                <option value="BE">🇧🇪 Belgique</option>
                                                <option value="CH">🇨🇭 Suisse</option>
                                                <option value="CA">🇨🇦 Canada</option>
                                                <option value="US">🇺🇸 États-Unis</option>
                                                <option value="GB">🇬🇧 Royaume-Uni</option>
                                                <option value="DE">🇩🇪 Allemagne</option>
                                                <option value="ES">🇪🇸 Espagne</option>
                                                <option value="IT">🇮🇹 Italie</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="language_code" class="form-label fw-semibold">Langue</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-language text-muted"></i>
                                            </span>
                                            <select id="language_code" v-model="createForm.language_code" class="form-select">
                                                <option value="fr-FR">Français</option>
                                                <option value="en-US">Anglais (US)</option>
                                                <option value="en-GB">Anglais (UK)</option>
                                                <option value="es-ES">Espagnol</option>
                                                <option value="de-DE">Allemand</option>
                                                <option value="it-IT">Italien</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="crawl_frequency" class="form-label fw-semibold">Fréquence d'analyse</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-sync text-muted"></i>
                                        </span>
                                        <select id="crawl_frequency" v-model="createForm.crawl_frequency" class="form-select">
                                            <option value="daily">Quotidienne</option>
                                            <option value="weekly">Hebdomadaire</option>
                                            <option value="monthly">Mensuelle</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer border-top-0 pt-4">
                                    <button type="button" class="btn btn-light" @click="showCreateModal = false">
                                        <i class="fas fa-times me-2"></i>
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn btn-gradient-primary" :disabled="createForm.processing">
                                        <span v-if="createForm.processing">
                                            <span class="spinner-border spinner-border-sm me-2"></span>
                                            Création...
                                        </span>
                                        <span v-else>
                                            <i class="fas fa-check me-2"></i>
                                            Créer le projet
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    projects: Array,
});

const showCreateModal = ref(false);

const createForm = useForm({
    name: '',
    website_url: '',
    country_code: 'FR',
    language_code: 'fr-FR',
    crawl_frequency: 'weekly',
});

const createProject = () => {
    createForm.post('/api/v1/projects', {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
            window.$toast?.success('Projet créé avec succès!');
        },
        onError: () => {
            window.$toast?.error('Erreur lors de la création du projet');
        },
    });
};

const openProject = (projectId) => {
    router.visit(`/projects/${projectId}`);
};

const editProject = (project) => {
    // TODO: Implement edit functionality
    window.$toast?.info('Modification en cours de développement');
};

const deleteProject = (project) => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Supprimer le projet ?',
            text: `Êtes-vous sûr de vouloir supprimer "${project.name}" ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(`/api/v1/projects/${project.id}`, {
                    onSuccess: () => {
                        window.$toast?.success('Projet supprimé avec succès');
                    },
                });
            }
        });
    }
};

const formatDate = (date) => {
    if (!date) return 'Jamais analysé';
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
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

.btn-gradient-primary:disabled {
    opacity: 0.7;
}

.shadow-hover {
    transition: all 0.3s ease;
}

.shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.cursor-pointer {
    cursor: pointer;
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
</style>
