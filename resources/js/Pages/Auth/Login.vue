<template>
    <Head title="Connexion" />

    <div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">SEO Master Pro</h1>
                <p class="text-gray-600">Connectez-vous à votre compte</p>
            </div>

            <div class="card">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="email" class="label">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="input"
                            :class="{'border-red-500': form.errors.email}"
                            required
                            autofocus
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
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

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" />
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>

                        <a href="#" class="text-sm text-primary-600 hover:text-primary-800">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                        <span v-if="form.processing">Connexion...</span>
                        <span v-else">Se connecter</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte ?
                        <Link href="/register" class="text-primary-600 hover:text-primary-800 font-medium">S'inscrire</Link>
                    </p>
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
