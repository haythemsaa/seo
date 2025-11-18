<template>
    <button
        :type="type"
        :class="buttonClass"
        :disabled="disabled || loading"
        @click="handleClick"
    >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        <i v-else-if="icon && iconPosition === 'left'" :class="icon" class="me-2"></i>
        <slot></slot>
        <i v-if="icon && iconPosition === 'right'" :class="icon" class="ms-2"></i>
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
        validator: (value) => ['button', 'submit', 'reset'].includes(value),
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'].includes(value),
    },
    outline: {
        type: Boolean,
        default: false,
    },
    gradient: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    block: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: '',
    },
    iconPosition: {
        type: String,
        default: 'left',
        validator: (value) => ['left', 'right'].includes(value),
    },
});

const emit = defineEmits(['click']);

const buttonClass = computed(() => {
    const classes = ['btn'];

    if (props.gradient && props.variant === 'primary') {
        classes.push('btn-gradient-primary');
    } else if (props.outline) {
        classes.push(`btn-outline-${props.variant}`);
    } else {
        classes.push(`btn-${props.variant}`);
    }

    if (props.size !== 'md') {
        classes.push(`btn-${props.size}`);
    }

    if (props.block) {
        classes.push('w-100');
    }

    return classes.join(' ');
});

const handleClick = (event) => {
    if (!props.disabled && !props.loading) {
        emit('click', event);
    }
};
</script>

<style scoped>
.btn-gradient-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    color: white;
}

.btn-gradient-primary:disabled {
    opacity: 0.7;
    transform: none;
}
</style>
