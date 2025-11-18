<template>
    <Head title="Paramètres" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="mb-4" data-aos="fade-down">
                <h1 class="h2 fw-bold mb-1">Paramètres</h1>
                <p class="text-muted mb-0">Gérez vos paramètres personnels et d'organisation</p>
            </div>

            <div class="row g-4">
                <!-- Sidebar Navigation -->
                <div class="col-lg-3" data-aos="fade-right">
                    <div class="card border-0 shadow-sm">
                        <div class="list-group list-group-flush">
                            <a
                                href="#"
                                class="list-group-item list-group-item-action"
                                :class="{ active: activeSection === 'profile' }"
                                @click.prevent="activeSection = 'profile'"
                            >
                                <i class="fas fa-user me-2"></i>
                                Profil
                            </a>
                            <a
                                href="#"
                                class="list-group-item list-group-item-action"
                                :class="{ active: activeSection === 'security' }"
                                @click.prevent="activeSection = 'security'"
                            >
                                <i class="fas fa-lock me-2"></i>
                                Sécurité
                            </a>
                            <a
                                href="#"
                                class="list-group-item list-group-item-action"
                                :class="{ active: activeSection === 'organization' }"
                                @click.prevent="activeSection = 'organization'"
                            >
                                <i class="fas fa-building me-2"></i>
                                Organisation
                            </a>
                            <a
                                href="#"
                                class="list-group-item list-group-item-action"
                                :class="{ active: activeSection === 'notifications' }"
                                @click.prevent="activeSection = 'notifications'"
                            >
                                <i class="fas fa-bell me-2"></i>
                                Notifications
                            </a>
                            <a
                                href="#"
                                class="list-group-item list-group-item-action"
                                :class="{ active: activeSection === 'api' }"
                                @click.prevent="activeSection = 'api'"
                            >
                                <i class="fas fa-code me-2"></i>
                                API & Intégrations
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9" data-aos="fade-left">
                    <!-- Profile Section -->
                    <div v-if="activeSection === 'profile'" class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Informations personnelles</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="updateProfile">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Prénom</label>
                                        <input type="text" v-model="profileForm.first_name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nom</label>
                                        <input type="text" v-model="profileForm.last_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" v-model="profileForm.email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Téléphone</label>
                                    <input type="tel" v-model="profileForm.phone" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Poste</label>
                                    <input type="text" v-model="profileForm.job_title" class="form-control" placeholder="ex: Consultant SEO">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div v-if="activeSection === 'security'" class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Sécurité et authentification</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">Mot de passe</h6>
                                <form @submit.prevent="updatePassword">
                                    <div class="mb-3">
                                        <label class="form-label">Mot de passe actuel</label>
                                        <input type="password" v-model="passwordForm.current_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nouveau mot de passe</label>
                                        <input type="password" v-model="passwordForm.new_password" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmer le mot de passe</label>
                                        <input type="password" v-model="passwordForm.confirm_password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                                </form>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1">Authentification à deux facteurs (2FA)</h6>
                                        <small class="text-muted">Renforcez la sécurité de votre compte</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" v-model="twoFactorEnabled" @change="toggle2FA">
                                    </div>
                                </div>
                                <div v-if="twoFactorEnabled" class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    2FA activé - Votre compte est protégé
                                </div>
                            </div>

                            <hr>

                            <div>
                                <h6 class="fw-semibold mb-3">Sessions actives</h6>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-laptop text-primary me-2"></i>
                                            <strong>Chrome sur Windows</strong>
                                            <br>
                                            <small class="text-muted">Paris, France • Actuellement</small>
                                        </div>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Section -->
                    <div v-if="activeSection === 'organization'" class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Paramètres d'organisation</h5>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="updateOrganization">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nom de l'organisation</label>
                                    <input type="text" v-model="orgForm.name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Site web</label>
                                    <input type="url" v-model="orgForm.website" class="form-control" placeholder="https://example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Adresse</label>
                                    <input type="text" v-model="orgForm.address" class="form-control">
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Ville</label>
                                        <input type="text" v-model="orgForm.city" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Code postal</label>
                                        <input type="text" v-model="orgForm.postal_code" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Pays</label>
                                    <select v-model="orgForm.country" class="form-select">
                                        <option value="FR">France</option>
                                        <option value="BE">Belgique</option>
                                        <option value="CH">Suisse</option>
                                        <option value="CA">Canada</option>
                                    </select>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-gradient-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Notifications Section -->
                    <div v-if="activeSection === 'notifications'" class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Préférences de notifications</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-3">Notifications par email</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.email_keyword_changes" id="email_keyword_changes">
                                    <label class="form-check-label" for="email_keyword_changes">
                                        Changements de positions des mots-clés
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.email_new_backlinks" id="email_new_backlinks">
                                    <label class="form-check-label" for="email_new_backlinks">
                                        Nouveaux backlinks détectés
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.email_audit_completed" id="email_audit_completed">
                                    <label class="form-check-label" for="email_audit_completed">
                                        Audits terminés
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.email_weekly_report" id="email_weekly_report">
                                    <label class="form-check-label" for="email_weekly_report">
                                        Rapport hebdomadaire
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-3">Notifications dans l'application</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.app_recommendations" id="app_recommendations">
                                    <label class="form-check-label" for="app_recommendations">
                                        Nouvelles recommandations IA
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" v-model="notifForm.app_critical_issues" id="app_critical_issues">
                                    <label class="form-check-label" for="app_critical_issues">
                                        Problèmes critiques détectés
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-gradient-primary" @click="saveNotifications">
                                    <i class="fas fa-save me-2"></i>
                                    Enregistrer les préférences
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- API Section -->
                    <div v-if="activeSection === 'api'" class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">API & Intégrations</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">Clés API</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Utilisez ces clés pour accéder à l'API SEO Master Pro
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Clé API publique</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control font-monospace" :value="apiKeys.public" readonly>
                                        <button class="btn btn-outline-secondary" @click="copyToClipboard(apiKeys.public)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Clé API privée</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control font-monospace" :value="apiKeys.private" readonly>
                                        <button class="btn btn-outline-secondary" @click="regenerateApiKey">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Ne partagez jamais votre clé privée</small>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h6 class="fw-semibold mb-3">Intégrations externes</h6>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fab fa-google text-danger fs-3 me-3"></i>
                                            <div>
                                                <strong>Google Search Console</strong>
                                                <br>
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Connecté
                                                </small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger">Déconnecter</button>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fab fa-google text-warning fs-3 me-3"></i>
                                            <div>
                                                <strong>Google Analytics</strong>
                                                <br>
                                                <small class="text-muted">Non connecté</small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-primary">Connecter</button>
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
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const activeSection = ref('profile');
const twoFactorEnabled = ref(false);

const profileForm = ref({
    first_name: 'Jean',
    last_name: 'Dupont',
    email: 'jean.dupont@example.com',
    phone: '+33 6 12 34 56 78',
    job_title: 'Consultant SEO',
});

const passwordForm = ref({
    current_password: '',
    new_password: '',
    confirm_password: '',
});

const orgForm = ref({
    name: 'Mon Agence SEO',
    website: 'https://example.com',
    address: '123 Rue de la Paix',
    city: 'Paris',
    postal_code: '75001',
    country: 'FR',
});

const notifForm = ref({
    email_keyword_changes: true,
    email_new_backlinks: true,
    email_audit_completed: true,
    email_weekly_report: false,
    app_recommendations: true,
    app_critical_issues: true,
});

const apiKeys = {
    public: 'pk_live_51234567890abcdef',
    private: '••••••••••••••••••••••••••••',
};

const updateProfile = () => {
    window.$toast?.success('Profil mis à jour avec succès');
};

const updatePassword = () => {
    if (passwordForm.value.new_password !== passwordForm.value.confirm_password) {
        window.$toast?.error('Les mots de passe ne correspondent pas');
        return;
    }
    window.$toast?.success('Mot de passe modifié avec succès');
    passwordForm.value = { current_password: '', new_password: '', confirm_password: '' };
};

const toggle2FA = () => {
    if (twoFactorEnabled.value) {
        window.$toast?.success('Authentification à deux facteurs activée');
    } else {
        window.$toast?.info('Authentification à deux facteurs désactivée');
    }
};

const updateOrganization = () => {
    window.$toast?.success('Organisation mise à jour avec succès');
};

const saveNotifications = () => {
    window.$toast?.success('Préférences de notifications enregistrées');
};

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    window.$toast?.success('Copié dans le presse-papier');
};

const regenerateApiKey = () => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Régénérer la clé API ?',
            text: 'L\'ancienne clé ne fonctionnera plus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Régénérer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                window.$toast?.success('Nouvelle clé API générée');
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

.list-group-item.active {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-color: #6366f1;
}
</style>
