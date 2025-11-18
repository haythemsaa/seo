<template>
    <Head title="Abonnement" />

    <AppLayout>
        <div class="container-fluid py-4">
            <div class="mb-4" data-aos="fade-down">
                <h1 class="h2 fw-bold mb-1">
                    <i class="fas fa-crown text-warning me-2"></i>
                    Abonnement & Facturation
                </h1>
                <p class="text-muted mb-0">Gérez votre abonnement et consultez votre historique de facturation</p>
            </div>

            <!-- Current Plan -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-shape bg-gradient-primary me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Plan {{ currentPlan.name }}</h5>
                                    <small class="text-muted">{{ currentPlan.price }}€/mois</small>
                                </div>
                            </div>
                            <p class="text-muted mb-3">{{ currentPlan.description }}</p>
                            <div class="d-flex gap-3 mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Renouvelé le {{ formatDate(currentPlan.next_billing_date) }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-credit-card me-1"></i>
                                    •••• {{ currentPlan.card_last4 }}
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-primary me-2" @click="showChangePlanModal = true">
                                <i class="fas fa-exchange-alt me-1"></i>
                                Changer de plan
                            </button>
                            <button class="btn btn-outline-danger" @click="cancelSubscription">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <small class="text-muted">Projets</small>
                                <i class="fas fa-folder-open text-primary"></i>
                            </div>
                            <h3 class="fw-bold mb-2">{{ usage.projects }} / {{ limits.projects }}</h3>
                            <div class="progress" style="height: 6px;">
                                <div
                                    class="progress-bar bg-primary"
                                    :style="{ width: (usage.projects / limits.projects * 100) + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <small class="text-muted">Mots-clés</small>
                                <i class="fas fa-key text-success"></i>
                            </div>
                            <h3 class="fw-bold mb-2">{{ usage.keywords }} / {{ limits.keywords }}</h3>
                            <div class="progress" style="height: 6px;">
                                <div
                                    class="progress-bar bg-success"
                                    :style="{ width: (usage.keywords / limits.keywords * 100) + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <small class="text-muted">Backlinks suivis</small>
                                <i class="fas fa-link text-info"></i>
                            </div>
                            <h3 class="fw-bold mb-2">{{ usage.backlinks }} / {{ limits.backlinks }}</h3>
                            <div class="progress" style="height: 6px;">
                                <div
                                    class="progress-bar bg-info"
                                    :style="{ width: (usage.backlinks / limits.backlinks * 100) + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <small class="text-muted">Rapports/mois</small>
                                <i class="fas fa-file-pdf text-danger"></i>
                            </div>
                            <h3 class="fw-bold mb-2">{{ usage.reports }} / {{ limits.reports }}</h3>
                            <div class="progress" style="height: 6px;">
                                <div
                                    class="progress-bar bg-danger"
                                    :style="{ width: (usage.reports / limits.reports * 100) + '%' }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Plans -->
            <div class="mb-4" data-aos="fade-up">
                <h5 class="fw-bold mb-3">Plans disponibles</h5>
                <div class="row g-4">
                    <div v-for="(plan, index) in plans" :key="plan.id" class="col-lg-3 col-md-6" data-aos="fade-up" :data-aos-delay="index * 100">
                        <div
                            class="card h-100 border"
                            :class="plan.id === currentPlan.id ? 'border-primary shadow' : 'border-0 shadow-sm'"
                        >
                            <div class="card-body">
                                <div v-if="plan.id === currentPlan.id" class="mb-2">
                                    <span class="badge bg-primary">Plan actuel</span>
                                </div>
                                <h5 class="fw-bold mb-1">{{ plan.name }}</h5>
                                <div class="mb-3">
                                    <span class="h2 fw-bold">{{ plan.price }}€</span>
                                    <small class="text-muted">/mois</small>
                                </div>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ plan.limits.projects }} projet(s)
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ plan.limits.keywords }} mots-clés
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ plan.limits.backlinks }} backlinks
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ plan.limits.reports }} rapports/mois
                                    </li>
                                    <li v-if="plan.features.whiteLabel" class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Rapports white label
                                    </li>
                                    <li v-if="plan.features.api" class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Accès API
                                    </li>
                                </ul>
                                <button
                                    v-if="plan.id !== currentPlan.id"
                                    class="btn w-100"
                                    :class="plan.recommended ? 'btn-gradient-primary' : 'btn-outline-primary'"
                                    @click="changePlan(plan)"
                                >
                                    {{ plan.price > currentPlan.price ? 'Passer à ce plan' : 'Rétrograder' }}
                                </button>
                                <button v-else class="btn btn-outline-secondary w-100" disabled>
                                    Plan actuel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Moyen de paiement</h5>
                        <button class="btn btn-sm btn-outline-primary" @click="updatePaymentMethod">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fab fa-cc-visa fs-2 text-primary"></i>
                        </div>
                        <div>
                            <strong>Visa se terminant par {{ currentPlan.card_last4 }}</strong>
                            <br>
                            <small class="text-muted">Expire 12/2026</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing History -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Historique de facturation</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3 text-center">Montant</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="invoice in invoices" :key="invoice.id">
                                    <td class="px-4 py-3">{{ formatDate(invoice.date) }}</td>
                                    <td class="px-4 py-3">{{ invoice.description }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <strong>{{ invoice.amount }}€</strong>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge" :class="invoice.status === 'paid' ? 'bg-success' : 'bg-warning'">
                                            {{ invoice.status === 'paid' ? 'Payé' : 'En attente' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <button class="btn btn-sm btn-outline-primary" @click="downloadInvoice(invoice)">
                                            <i class="fas fa-download me-1"></i>
                                            PDF
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Plan Modal -->
        <Teleport to="body">
            <div v-if="showChangePlanModal" class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Changer de plan</h5>
                            <button type="button" class="btn-close" @click="showChangePlanModal = false"></button>
                        </div>
                        <div class="modal-body">
                            <p>Sélectionnez un nouveau plan dans la liste ci-dessus.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" @click="showChangePlanModal = false">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const showChangePlanModal = ref(false);

const currentPlan = {
    id: 2,
    name: 'Professional',
    price: 99,
    description: 'Pour les consultants et petites agences',
    next_billing_date: '2025-02-15',
    card_last4: '4242',
};

const usage = {
    projects: 5,
    keywords: 287,
    backlinks: 1234,
    reports: 3,
};

const limits = {
    projects: 10,
    keywords: 500,
    backlinks: 5000,
    reports: 10,
};

const plans = [
    {
        id: 1,
        name: 'Starter',
        price: 29,
        limits: { projects: 3, keywords: 100, backlinks: 1000, reports: 5 },
        features: { whiteLabel: false, api: false },
        recommended: false,
    },
    {
        id: 2,
        name: 'Professional',
        price: 99,
        limits: { projects: 10, keywords: 500, backlinks: 5000, reports: 10 },
        features: { whiteLabel: true, api: false },
        recommended: true,
    },
    {
        id: 3,
        name: 'Agency',
        price: 249,
        limits: { projects: 50, keywords: 5000, backlinks: 50000, reports: 50 },
        features: { whiteLabel: true, api: true },
        recommended: false,
    },
    {
        id: 4,
        name: 'Enterprise',
        price: 499,
        limits: { projects: -1, keywords: -1, backlinks: -1, reports: -1 },
        features: { whiteLabel: true, api: true },
        recommended: false,
    },
];

const invoices = [
    { id: 1, date: '2025-01-15', description: 'Abonnement Professional - Janvier 2025', amount: 99, status: 'paid' },
    { id: 2, date: '2024-12-15', description: 'Abonnement Professional - Décembre 2024', amount: 99, status: 'paid' },
    { id: 3, date: '2024-11-15', description: 'Abonnement Professional - Novembre 2024', amount: 99, status: 'paid' },
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const changePlan = (plan) => {
    if (window.Swal) {
        window.Swal.fire({
            title: `Passer au plan ${plan.name} ?`,
            text: `Vous serez facturé ${plan.price}€/mois`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler',
        }).then((result) => {
            if (result.isConfirmed) {
                window.$toast?.success(`Plan changé pour ${plan.name}`);
            }
        });
    }
};

const cancelSubscription = () => {
    if (window.Swal) {
        window.Swal.fire({
            title: 'Annuler votre abonnement ?',
            text: 'Votre accès sera maintenu jusqu\'à la fin de la période de facturation',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder',
        }).then((result) => {
            if (result.isConfirmed) {
                window.$toast?.info('Abonnement annulé');
            }
        });
    }
};

const updatePaymentMethod = () => {
    window.$toast?.info('Mise à jour du moyen de paiement (à implémenter)');
};

const downloadInvoice = (invoice) => {
    window.$toast?.success(`Téléchargement de la facture ${invoice.id}`);
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

.icon-shape {
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}
</style>
