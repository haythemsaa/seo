<template>
    <Head title="Inscription" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">SEO Master Pro</h1>
                <p class="text-gray-600">Créez votre compte gratuitement</p>
            </div>

            <div class="card">
                <form @submit.prevent="submit">
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="label">Prénom</label>
                            <input
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                class="input"
                                :class="{'border-red-500': form.errors.first_name}"
                                required
                            />
                            <p v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">{{ form.errors.first_name }}</p>
                        </div>

                        <div>
                            <label for="last_name" class="label">Nom</label>
                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                class="input"
                                :class="{'border-red-500': form.errors.last_name}"
                                required
                            />
                            <p v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">{{ form.errors.last_name }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="label">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="input"
                            :class="{'border-red-500': form.errors.email}"
                            required
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="organization_name" class="label">Nom de l'organisation</label>
                        <input
                            id="organization_name"
                            v-model="form.organization_name"
                            type="text"
                            class="input"
                            :class="{'border-red-500': form.errors.organization_name}"
                            required
                        />
                        <p v-if="form.errors.organization_name" class="text-red-500 text-sm mt-1">{{ form.errors.organization_name }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="label">Mot de passe</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="input"
                            :class="{'border-red-500': form.errors.password}"
                            required
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="label">Confirmer le mot de passe</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="input"
                            required
                        />
                    </div>

                    <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                        <span v-if="form.processing">Création du compte...</span>
                        <span v-else>Créer mon compte</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Déjà un compte ?
                        <Link href="/login" class="text-primary-600 hover:text-primary-800 font-medium">Se connecter</Link>
                    </p>
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>En créant un compte, vous acceptez nos <a href="#" class="text-primary-600 hover:underline">conditions d'utilisation</a></p>
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
