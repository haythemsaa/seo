<template>
    <Head title="Connexion" />

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-primary p-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="text-center mb-4" data-aos="fade-down">
                        <div class="mb-3">
                            <i class="fas fa-chart-line text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h1 class="h2 fw-bold text-white mb-2">SEO Master Pro</h1>
                        <p class="text-white-50">Connectez-vous à votre compte</p>
                    </div>

                    <div class="card shadow-lg border-0" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-body p-4">
                            <form @submit.prevent="submit">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
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
                                            placeholder="votre@email.com"
                                            required
                                            autofocus
                                        />
                                        <div v-if="form.errors.email" class="invalid-feedback">
                                            {{ form.errors.email }}
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
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input
                                            v-model="form.remember"
                                            type="checkbox"
                                            class="form-check-input"
                                            id="remember"
                                        />
                                        <label class="form-check-label small text-muted" for="remember">
                                            Se souvenir de moi
                                        </label>
                                    </div>

                                    <a href="#" class="small text-primary text-decoration-none">
                                        Mot de passe oublié ?
                                    </a>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-gradient-primary w-100 py-2 fw-semibold"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Connexion...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Se connecter
                                    </span>
                                </button>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <p class="small text-muted mb-0">
                                    Pas encore de compte ?
                                    <Link href="/register" class="text-primary text-decoration-none fw-semibold">
                                        S'inscrire gratuitement
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-white-50">
                            © 2025 SEO Master Pro - Tous droits réservés
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
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
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
