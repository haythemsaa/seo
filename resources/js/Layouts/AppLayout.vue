<template>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'show': sidebarOpen }">
            <div class="d-flex flex-column h-100">
                <!-- Logo -->
                <div class="p-4 border-bottom border-secondary">
                    <Link href="/" class="navbar-brand d-block text-center">
                        <i class="fas fa-chart-line me-2"></i>
                        SEO Master Pro
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="flex-grow-1 py-3">
                    <Link v-for="item in navItems" :key="item.name"
                          :href="item.href"
                          class="nav-link"
                          :class="{ 'active': isActive(item.href) }">
                        <i :class="['fas', item.icon]"></i>
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- User Section -->
                <div class="p-3 border-top border-secondary">
                    <div class="dropdown dropup">
                        <button class="btn btn-link text-white text-decoration-none w-100 text-start dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-gradient-primary me-2" style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-white small"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="d-block text-white fw-semibold">{{ auth.user?.name }}</small>
                                    <small class="text-white-50" style="font-size: 0.7rem;">
                                        {{ auth.user?.organization?.subscription_plan?.toUpperCase() || 'FREE' }}
                                    </small>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark w-100">
                            <li><Link class="dropdown-item" href="/settings"><i class="fas fa-cog me-2"></i>Paramètres</Link></li>
                            <li><Link class="dropdown-item" href="/subscription"><i class="fas fa-crown me-2"></i>Abonnement</Link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><Link class="dropdown-item" href="/logout" method="post"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-link d-lg-none" @click="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <button class="btn btn-link position-relative" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fs-5 text-muted"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    3
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                                <li class="dropdown-header">Notifications</li>
                                <li><a class="dropdown-item" href="#">
                                    <div class="d-flex">
                                        <div class="icon-shape bg-gradient-success me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                                            <i class="fas fa-arrow-up text-white small"></i>
                                        </div>
                                        <div class="small">
                                            <strong>Nouveau top 3!</strong><br>
                                            <span class="text-muted">"agence seo" est en position 2</span>
                                        </div>
                                    </div>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center text-primary" href="#">Voir tout</a></li>
                            </ul>
                        </div>

                        <!-- Search -->
                        <form class="d-none d-md-block me-3">
                            <div class="input-group">
                                <input type="search" class="form-control form-control-sm" placeholder="Rechercher...">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Subscription Badge -->
                        <span class="badge bg-gradient-primary px-3 py-2">
                            {{ auth.user?.organization?.subscription_plan?.toUpperCase() || 'FREE' }}
                        </span>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            <div v-if="flash.success" class="container-fluid mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ flash.success }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>

            <div v-if="flash.error" class="container-fluid mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ flash.error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
            <footer class="mt-5 py-4 bg-white border-top">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start">
                            <small class="text-muted">
                                © 2025 SEO Master Pro. Tous droits réservés.
                            </small>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <small class="text-muted">
                                <a href="#" class="text-decoration-none me-3">Documentation</a>
                                <a href="#" class="text-decoration-none me-3">Support</a>
                                <a href="#" class="text-decoration-none">API</a>
                            </small>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth);
const flash = computed(() => page.props.flash);

const sidebarOpen = ref(false);

const navItems = [
    { name: 'Dashboard', href: '/dashboard', icon: 'fa-home' },
    { name: 'Projets', href: '/projects', icon: 'fa-folder-open' },
    { name: 'Mots-clés', href: '/keywords', icon: 'fa-key' },
    { name: 'Backlinks', href: '/backlinks', icon: 'fa-link' },
    { name: 'Audits', href: '/audits', icon: 'fa-search' },
    { name: 'Rapports', href: '/reports', icon: 'fa-file-alt' },
    { name: 'Recommandations', href: '/recommendations', icon: 'fa-lightbulb' },
    { name: 'Paramètres', href: '/settings', icon: 'fa-cog' },
];

const isActive = (href) => {
    return page.url.startsWith(href);
};

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<style scoped>
@media (max-width: 991px) {
    .sidebar {
        position: fixed;
        z-index: 1050;
    }

    .main-content {
        margin-left: 0 !important;
    }
}
</style>
