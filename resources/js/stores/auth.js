import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export const useAuthStore = defineStore('auth', () => {
    const page = usePage();

    const user = computed(() => page.props.auth?.user || null);
    const organization = computed(() => page.props.auth?.user?.organization || null);
    const subscriptionPlan = computed(() => organization.value?.subscription_plan || 'free');

    const isAuthenticated = computed(() => user.value !== null);
    const isAdmin = computed(() => user.value?.role === 'admin');

    const hasFeature = (feature) => {
        const features = {
            free: ['basic_tracking', 'basic_reports'],
            starter: ['basic_tracking', 'basic_reports', 'backlinks', 'audits'],
            professional: ['basic_tracking', 'basic_reports', 'backlinks', 'audits', 'white_label', 'recommendations'],
            agency: ['basic_tracking', 'basic_reports', 'backlinks', 'audits', 'white_label', 'recommendations', 'api', 'priority_support'],
            enterprise: ['all'],
        };

        const planFeatures = features[subscriptionPlan.value] || features.free;
        return planFeatures.includes('all') || planFeatures.includes(feature);
    };

    const getLimits = () => {
        const limits = {
            free: { projects: 1, keywords: 10, backlinks: 100, reports: 1 },
            starter: { projects: 3, keywords: 100, backlinks: 1000, reports: 5 },
            professional: { projects: 10, keywords: 500, backlinks: 5000, reports: 10 },
            agency: { projects: 50, keywords: 5000, backlinks: 50000, reports: 50 },
            enterprise: { projects: -1, keywords: -1, backlinks: -1, reports: -1 },
        };
        return limits[subscriptionPlan.value] || limits.free;
    };

    return {
        user,
        organization,
        subscriptionPlan,
        isAuthenticated,
        isAdmin,
        hasFeature,
        getLimits,
    };
});
