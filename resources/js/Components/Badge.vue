<template>
    <span class="badge" :class="badgeClass">
        <i v-if="icon" :class="icon" class="me-1"></i>
        <slot></slot>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'].includes(value),
    },
    pill: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: '',
    },
    gradient: {
        type: Boolean,
        default: false,
    },
});

const badgeClass = computed(() => {
    const classes = [];

    if (props.gradient) {
        classes.push(`bg-gradient-${props.variant}`);
        classes.push('text-white');
    } else {
        classes.push(`bg-${props.variant}`);
    }

    if (props.pill) {
        classes.push('rounded-pill');
    }

    return classes.join(' ');
});
</script>

<style scoped>
.bg-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}
</style>
