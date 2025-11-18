<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <Link href="/" class="text-xl font-bold text-primary-600">
                                SEO Master Pro
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <NavLink href="/dashboard" :active="$page.url === '/dashboard'">
                                Dashboard
                            </NavLink>
                            <NavLink href="/projects" :active="$page.url.startsWith('/projects')">
                                Projets
                            </NavLink>
                            <NavLink href="/keywords" :active="$page.url.startsWith('/keywords')">
                                Mots-clés
                            </NavLink>
                            <NavLink href="/reports" :active="$page.url.startsWith('/reports')">
                                Rapports
                            </NavLink>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <!-- Subscription Badge -->
                        <div v-if="auth.user" class="mr-4 px-3 py-1 bg-primary-100 text-primary-800 text-xs font-semibold rounded-full">
                            {{ auth.user.organization?.subscription_plan?.toUpperCase() || 'FREE' }}
                        </div>

                        <!-- User Dropdown -->
                        <div v-if="auth.user" class="relative">
                            <button
                                @click="showUserMenu = !showUserMenu"
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900"
                            >
                                <span>{{ auth.user.name }}</span>
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div
                                v-if="showUserMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50"
                                @click.away="showUserMenu = false"
                            >
                                <Link href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Paramètres
                                </Link>
                                <Link href="/subscription" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Abonnement
                                </Link>
                                <hr class="my-1" />
                                <Link href="/logout" method="post" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Déconnexion
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <!-- Flash Messages -->
            <div v-if="flash.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <p class="text-green-700">{{ flash.success }}</p>
                </div>
            </div>

            <div v-if="flash.error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <p class="text-red-700">{{ flash.error }}</p>
                </div>
            </div>

            <slot />
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';

const page = usePage();
const auth = page.props.auth;
const flash = page.props.flash;

const showUserMenu = ref(false);
</script>
