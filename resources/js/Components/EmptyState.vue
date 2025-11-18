<template>
    <div class="text-center py-5" :class="containerClass">
        <div class="icon-shape mx-auto mb-4" :class="iconBgClass" style="width: 80px; height: 80px;">
            <i class="text-white fs-1" :class="icon"></i>
        </div>
        <h5 class="fw-bold mb-2">{{ title }}</h5>
        <p class="text-muted mb-4">{{ description }}</p>
        <button v-if="actionText" class="btn btn-gradient-primary" @click="$emit('action')">
            <i v-if="actionIcon" :class="actionIcon" class="me-2"></i>
            {{ actionText }}
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    icon: {
        type: String,
        default: 'fas fa-inbox',
    },
    iconBg: {
        type: String,
        default: 'primary',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
    actionText: {
        type: String,
        default: '',
    },
    actionIcon: {
        type: String,
        default: '',
    },
    containerClass: {
        type: String,
        default: '',
    },
});

defineEmits(['action']);

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
