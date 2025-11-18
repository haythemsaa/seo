<template>
    <Head title="Projets" />

    <AppLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Mes Projets</h1>
                <button @click="showCreateModal = true" class="btn btn-primary">
                    + Nouveau Projet
                </button>
            </div>

            <div v-if="projects.length === 0" class="card text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun projet</h3>
                <p class="text-gray-600 mb-4">Créez votre premier projet pour commencer à suivre votre SEO</p>
                <button @click="showCreateModal = true" class="btn btn-primary">
                    Créer un projet
                </button>
            </div>

            <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="project in projects" :key="project.id" class="card hover:shadow-lg transition-shadow cursor-pointer" @click="openProject(project.id)">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ project.name }}</h3>
                            <p class="text-sm text-gray-500">{{ project.website_url }}</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full"
                            :class="project.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                        >
                            {{ project.is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Mots-clés</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.keywords_count || 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Backlinks</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.backlinks_count || 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Visibilité</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.visibility_score || 0 }}%</p>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500">
                        Dernière analyse: {{ formatDate(project.last_crawled_at) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Project Modal -->
        <Teleport to="body">
            <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" @click.self="showCreateModal = false">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Créer un nouveau projet</h2>

                    <form @submit.prevent="createProject">
                        <div class="mb-4">
                            <label class="label">Nom du projet</label>
                            <input v-model="createForm.name" type="text" class="input" required />
                        </div>

                        <div class="mb-4">
                            <label class="label">URL du site</label>
                            <input v-model="createForm.website_url" type="url" class="input" placeholder="https://example.com" required />
                        </div>

                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="label">Pays</label>
                                <select v-model="createForm.country_code" class="input">
                                    <option value="FR">France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="CH">Suisse</option>
                                    <option value="CA">Canada</option>
                                    <option value="US">États-Unis</option>
                                </select>
                            </div>

                            <div>
                                <label class="label">Langue</label>
                                <select v-model="createForm.language_code" class="input">
                                    <option value="fr-FR">Français</option>
                                    <option value="en-US">Anglais</option>
                                    <option value="es-ES">Espagnol</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <button type="button" @click="showCreateModal = false" class="btn btn-secondary">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary" :disabled="createForm.processing">
                                Créer le projet
                            </button>
                        </div>
                    </form>
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
});

const createProject = () => {
    createForm.post('/api/v1/projects', {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

const openProject = (projectId) => {
    router.visit(`/projects/${projectId}`);
};

const formatDate = (date) => {
    if (!date) return 'Jamais';
    return new Date(date).toLocaleDateString('fr-FR');
};
</script>
