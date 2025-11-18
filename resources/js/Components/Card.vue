<template>
    <div class="card" :class="cardClass" :data-aos="aos" :data-aos-delay="aosDelay">
        <div v-if="!hideHeader && (title || $slots.header)" class="card-header" :class="headerClass">
            <slot name="header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i v-if="icon" :class="icon" class="me-2"></i>
                        {{ title }}
                    </h5>
                    <slot name="actions"></slot>
                </div>
            </slot>
        </div>
        <div class="card-body" :class="bodyClass">
            <slot></slot>
        </div>
        <div v-if="!hideFooter && $slots.footer" class="card-footer" :class="footerClass">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    icon: {
        type: String,
        default: '',
    },
    shadow: {
        type: String,
        default: 'sm',
        validator: (value) => ['none', 'sm', 'md', 'lg', 'hover'].includes(value),
    },
    border: {
        type: Boolean,
        default: false,
    },
    hideHeader: {
        type: Boolean,
        default: false,
    },
    hideFooter: {
        type: Boolean,
        default: true,
    },
    headerClass: {
        type: String,
        default: 'bg-white border-bottom',
    },
    bodyClass: {
        type: String,
        default: '',
    },
    footerClass: {
        type: String,
        default: 'bg-light border-top',
    },
    aos: {
        type: String,
        default: '',
    },
    aosDelay: {
        type: [String, Number],
        default: 0,
    },
});

const cardClass = computed(() => {
    const classes = [];

    if (!props.border) {
        classes.push('border-0');
    }

    const shadows = {
        none: '',
        sm: 'shadow-sm',
        md: 'shadow',
        lg: 'shadow-lg',
        hover: 'shadow-sm shadow-hover',
    };

    if (shadows[props.shadow]) {
        classes.push(shadows[props.shadow]);
    }

    return classes.join(' ');
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
</style>
