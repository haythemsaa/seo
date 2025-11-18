<template>
    <Head title="Bienvenue" />

    <div class="min-vh-100 bg-gradient-primary">
        <!-- Hero Section -->
        <section class="py-5">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0" data-aos="fade-right">
                        <h1 class="display-3 fw-bold text-white mb-4">
                            SEO Master Pro
                        </h1>
                        <p class="lead text-white-50 mb-4">
                            Plateforme SaaS tout-en-un de référencement naturel et payant
                        </p>
                        <p class="text-white mb-5">
                            Optimisez votre visibilité en ligne avec notre suite complète d'outils SEO professionnels.
                            Suivez vos positions, analysez vos backlinks, auditez votre site et recevez des recommandations intelligentes.
                        </p>
                        <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap">
                            <Link href="/register" class="btn btn-light btn-lg px-4 py-3 shadow-lg">
                                <i class="fas fa-rocket me-2"></i>
                                Démarrer gratuitement
                            </Link>
                            <Link href="/login" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Se connecter
                            </Link>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                        <div class="position-relative">
                            <div class="card border-0 shadow-2xl" style="border-radius: 1rem;">
                                <div class="card-body p-4">
                                    <div class="text-center mb-4">
                                        <i class="fas fa-chart-line text-primary" style="font-size: 4rem;"></i>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="text-primary fw-bold h3 mb-0">+250%</div>
                                                <small class="text-muted">Trafic organique</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="text-success fw-bold h3 mb-0">Top 3</div>
                                                <small class="text-muted">Mots-clés</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="text-warning fw-bold h3 mb-0">98/100</div>
                                                <small class="text-muted">Score SEO</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <div class="text-info fw-bold h3 mb-0">500+</div>
                                                <small class="text-muted">Backlinks</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5">
            <div class="container py-5">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="display-5 fw-bold text-white mb-3">
                        Tout ce dont vous avez besoin pour dominer les SERPs
                    </h2>
                    <p class="lead text-white-50">
                        Une suite complète d'outils professionnels pour votre stratégie SEO
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-primary mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-chart-line text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Suivi de Positionnement</h4>
                                <p class="text-muted mb-0">
                                    Tracking multi-dispositifs et géolocalisé de vos mots-clés avec historique détaillé et alertes automatiques.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-success mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-search text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Audit Technique</h4>
                                <p class="text-muted mb-0">
                                    Analyse complète de votre site avec détection de 50+ problèmes SEO et recommandations priorisées.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-warning mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-link text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Analyse Backlinks</h4>
                                <p class="text-muted mb-0">
                                    Surveillance de votre profil de liens avec métriques de qualité (DA, PA, TF, CF) et détection de liens perdus.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-info mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-robot text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">IA Intégrée</h4>
                                <p class="text-muted mb-0">
                                    Recommandations contextuelles en français avec scoring impact/effort pour prioriser vos actions.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-danger mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-file-pdf text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Rapports White Label</h4>
                                <p class="text-muted mb-0">
                                    Générez des rapports PDF personnalisés avec votre branding pour impressionner vos clients.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body p-4 text-center">
                                <div class="icon-shape bg-gradient-secondary mb-4 mx-auto" style="width: 64px; height: 64px;">
                                    <i class="fas fa-plug text-white fs-3"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Intégrations</h4>
                                <p class="text-muted mb-0">
                                    Connectez Google Search Console, Analytics, et utilisez notre API REST complète.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5">
            <div class="container py-5">
                <div class="card border-0 shadow-2xl bg-white" style="border-radius: 1rem;" data-aos="zoom-in">
                    <div class="card-body p-5 text-center">
                        <h2 class="display-6 fw-bold mb-3">
                            Prêt à booster votre SEO ?
                        </h2>
                        <p class="lead text-muted mb-4">
                            Commencez gratuitement dès aujourd'hui. Aucune carte bancaire requise.
                        </p>
                        <Link href="/register" class="btn btn-gradient-primary btn-lg px-5 py-3">
                            <i class="fas fa-rocket me-2"></i>
                            Créer mon compte gratuit
                        </Link>
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success me-2"></i>Essai gratuit de 14 jours
                                <i class="fas fa-check-circle text-success ms-3 me-2"></i>Sans engagement
                                <i class="fas fa-check-circle text-success ms-3 me-2"></i>Support premium
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-4 border-top border-white border-opacity-10">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <small class="text-white-50">
                            © 2025 SEO Master Pro. Tous droits réservés.
                        </small>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small>
                            <a href="#" class="text-white-50 text-decoration-none me-3">Documentation</a>
                            <a href="#" class="text-white-50 text-decoration-none me-3">Support</a>
                            <a href="#" class="text-white-50 text-decoration-none">API</a>
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
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

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    color: white;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 1rem !important;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.icon-shape {
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}
</style>
