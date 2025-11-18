<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="modelValue" class="modal d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5);" @click.self="close">
                <div class="modal-dialog modal-dialog-centered" :class="sizeClass">
                    <div class="modal-content border-0 shadow-lg" data-aos="zoom-in" data-aos-duration="300">
                        <div v-if="!hideHeader" class="modal-header" :class="{ 'border-bottom-0': !showHeaderBorder }">
                            <h5 class="modal-title fw-bold">
                                <i v-if="icon" :class="icon" class="me-2"></i>
                                {{ title }}
                            </h5>
                            <button v-if="!hideClose" type="button" class="btn-close" @click="close"></button>
                        </div>
                        <div class="modal-body">
                            <slot></slot>
                        </div>
                        <div v-if="!hideFooter" class="modal-footer" :class="{ 'border-top-0': !showFooterBorder }">
                            <slot name="footer">
                                <button type="button" class="btn btn-light" @click="close">
                                    {{ cancelText }}
                                </button>
                                <button v-if="confirmText" type="button" class="btn btn-gradient-primary" @click="confirm">
                                    {{ confirmText }}
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: '',
    },
    icon: {
        type: String,
        default: '',
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value),
    },
    hideHeader: {
        type: Boolean,
        default: false,
    },
    hideFooter: {
        type: Boolean,
        default: false,
    },
    hideClose: {
        type: Boolean,
        default: false,
    },
    showHeaderBorder: {
        type: Boolean,
        default: true,
    },
    showFooterBorder: {
        type: Boolean,
        default: true,
    },
    cancelText: {
        type: String,
        default: 'Annuler',
    },
    confirmText: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'confirm', 'close']);

const sizeClass = computed(() => {
    const sizes = {
        sm: 'modal-sm',
        md: '',
        lg: 'modal-lg',
        xl: 'modal-xl',
    };
    return sizes[props.size] || '';
});

const close = () => {
    emit('update:modelValue', false);
    emit('close');
};

const confirm = () => {
    emit('confirm');
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

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
