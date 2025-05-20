<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { useUserStore } from '@/stores/users';
import { onMounted } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}


withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
const userStore = useUserStore();

onMounted(async () => {
    if (!userStore.user) {
        const res = await fetch('/user');
        const data = await res.json();
        userStore.setUser(data);
    }
});


</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
