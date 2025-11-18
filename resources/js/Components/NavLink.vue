<template>
    <Link
        :href="href"
        class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors"
        :class="{
            'border-primary-500 text-gray-900': active,
            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': !active
        }"
    >
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    active: Boolean,
});
</script>
