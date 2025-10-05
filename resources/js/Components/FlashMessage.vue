<script setup>
import { computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';

const page = usePage();
const flash = computed(() => page.props.flash || {});

watch(
    () => flash.value,
    (value) => {
        if (value.success) {
            message.success(value.success);
        }
        if (value.error) {
            message.error(value.error);
        }
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <!-- This component doesn't render anything visible -->
</template>
