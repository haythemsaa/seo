<template>
    <div class="text-center py-5">
        <div class="spinner-border" :class="colorClass" role="status" :style="{ width: size, height: size }">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p v-if="message" class="text-muted mt-3">{{ message }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: String,
        default: '3rem',
    },
    color: {
        type: String,
        default: 'primary',
    },
    message: {
        type: String,
        default: '',
    },
});

const colorClass = computed(() => `text-${props.color}`);
</script>
