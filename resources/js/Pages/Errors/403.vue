<template>
    <Head title="Accès refusé - 403" />

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-warning">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">
                    <div data-aos="zoom-in">
                        <div class="mb-4">
                            <h1 class="display-1 fw-bold text-white mb-3" style="font-size: 8rem;">403</h1>
                            <div class="icon-shape bg-white mx-auto mb-4" style="width: 100px; height: 100px;">
                                <i class="fas fa-lock text-warning" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h2 class="h3 fw-bold text-white mb-3">Accès refusé</h2>
                        <p class="text-white-50 mb-5">
                            Vous n'avez pas les permissions nécessaires pour accéder à cette page.
                        </p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <Link href="/dashboard" class="btn btn-light btn-lg px-4">
                                <i class="fas fa-home me-2"></i>
                                Retour au dashboard
                            </Link>
                            <Link href="/subscription" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-crown me-2"></i>
                                Voir les plans
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<style scoped>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.icon-shape {
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
