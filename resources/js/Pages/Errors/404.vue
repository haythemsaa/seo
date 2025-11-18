<template>
    <Head title="Page non trouvée - 404" />

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-primary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">
                    <div data-aos="zoom-in">
                        <div class="mb-4">
                            <h1 class="display-1 fw-bold text-white mb-3" style="font-size: 8rem;">404</h1>
                            <div class="icon-shape bg-white mx-auto mb-4" style="width: 100px; height: 100px;">
                                <i class="fas fa-search text-primary" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h2 class="h3 fw-bold text-white mb-3">Page non trouvée</h2>
                        <p class="text-white-50 mb-5">
                            Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                        </p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <Link href="/dashboard" class="btn btn-light btn-lg px-4">
                                <i class="fas fa-home me-2"></i>
                                Retour au dashboard
                            </Link>
                            <button class="btn btn-outline-light btn-lg px-4" @click="goBack">
                                <i class="fas fa-arrow-left me-2"></i>
                                Page précédente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

const goBack = () => {
    window.history.back();
};
</script>

<style scoped>
.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.icon-shape {
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
