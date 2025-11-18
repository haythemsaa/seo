<template>
    <Head title="Inscription" />

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-primary p-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-6">
                    <div class="text-center mb-4" data-aos="fade-down">
                        <div class="mb-3">
                            <i class="fas fa-chart-line text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h1 class="h2 fw-bold text-white mb-2">SEO Master Pro</h1>
                        <p class="text-white-50">Créez votre compte gratuitement</p>
                    </div>

                    <div class="card shadow-lg border-0" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-body p-4">
                            <form @submit.prevent="submit">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-semibold">Prénom</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input
                                                id="first_name"
                                                v-model="form.first_name"
                                                type="text"
                                                class="form-control border-start-0 ps-0"
                                                :class="{'is-invalid': form.errors.first_name}"
                                                placeholder="Jean"
                                                required
                                            />
                                            <div v-if="form.errors.first_name" class="invalid-feedback">
                                                {{ form.errors.first_name }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-semibold">Nom</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input
                                                id="last_name"
                                                v-model="form.last_name"
                                                type="text"
                                                class="form-control border-start-0 ps-0"
                                                :class="{'is-invalid': form.errors.last_name}"
                                                placeholder="Dupont"
                                                required
                                            />
                                            <div v-if="form.errors.last_name" class="invalid-feedback">
                                                {{ form.errors.last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email professionnel</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="form-control border-start-0 ps-0"
                                            :class="{'is-invalid': form.errors.email}"
                                            placeholder="votre@entreprise.com"
                                            required
                                        />
                                        <div v-if="form.errors.email" class="invalid-feedback">
                                            {{ form.errors.email }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="organization_name" class="form-label fw-semibold">Nom de l'organisation</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-building text-muted"></i>
                                        </span>
                                        <input
                                            id="organization_name"
                                            v-model="form.organization_name"
                                            type="text"
                                            class="form-control border-start-0 ps-0"
                                            :class="{'is-invalid': form.errors.organization_name}"
                                            placeholder="Mon entreprise"
                                            required
                                        />
                                        <div v-if="form.errors.organization_name" class="invalid-feedback">
                                            {{ form.errors.organization_name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input
                                            id="password"
                                            v-model="form.password"
                                            type="password"
                                            class="form-control border-start-0 ps-0"
                                            :class="{'is-invalid': form.errors.password}"
                                            placeholder="••••••••"
                                            required
                                        />
                                        <div v-if="form.errors.password" class="invalid-feedback">
                                            {{ form.errors.password }}
                                        </div>
                                    </div>
                                    <small class="text-muted">Minimum 8 caractères, avec majuscules et chiffres</small>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-semibold">Confirmer le mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            type="password"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="••••••••"
                                            required
                                        />
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-gradient-primary w-100 py-2 fw-semibold"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Création du compte...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-rocket me-2"></i>
                                        Créer mon compte gratuitement
                                    </span>
                                </button>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <p class="small text-muted mb-0">
                                    Déjà un compte ?
                                    <Link href="/login" class="text-primary text-decoration-none fw-semibold">
                                        Se connecter
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-white-50">
                            En créant un compte, vous acceptez nos
                            <a href="#" class="text-white text-decoration-none">conditions d'utilisation</a>
                            et notre
                            <a href="#" class="text-white text-decoration-none">politique de confidentialité</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    organization_name: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        preserveScroll: true,
    });
};
</script>

<style scoped>
.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

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

.card {
    border-radius: 1rem;
    backdrop-filter: blur(10px);
}

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

.form-control {
    border-radius: 0 0.5rem 0.5rem 0;
}
</style>
