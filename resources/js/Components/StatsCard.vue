<template>
    <div class="card border-0 shadow-hover h-100" :data-aos="aos" :data-aos-delay="aosDelay">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="icon-shape me-3" :class="iconBgClass" style="width: 48px; height: 48px;">
                    <i class="text-white" :class="icon"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted small mb-1">{{ label }}</p>
                    <h3 class="fw-bold mb-0">{{ value }}</h3>
                    <small v-if="change !== undefined" :class="changeClass">
                        <i :class="changeIcon"></i>
                        {{ Math.abs(change) }}{{ changeUnit }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
    iconBg: {
        type: String,
        default: 'primary',
    },
    change: {
        type: Number,
        default: undefined,
    },
    changeUnit: {
        type: String,
        default: '%',
    },
    aos: {
        type: String,
        default: 'fade-up',
    },
    aosDelay: {
        type: [String, Number],
        default: 0,
    },
});

const iconBgClass = computed(() => {
    const gradients = {
        primary: 'bg-gradient-primary',
        success: 'bg-gradient-success',
        warning: 'bg-gradient-warning',
        info: 'bg-gradient-info',
        danger: 'bg-gradient-danger',
    };
    return gradients[props.iconBg] || 'bg-gradient-primary';
});

const changeClass = computed(() => {
    if (props.change === undefined) return '';
    return props.change > 0 ? 'text-success' : 'text-danger';
});

const changeIcon = computed(() => {
    if (props.change === undefined) return '';
    return props.change > 0 ? 'fas fa-arrow-up me-1' : 'fas fa-arrow-down me-1';
});
</script>

<style scoped>
.shadow-hover {
    transition: all 0.3s ease;
}

.shadow-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
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
</style>
