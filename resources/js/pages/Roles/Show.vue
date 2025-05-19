<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ role: any }>();
const breadcrumbs = [
  { title: 'Roles', href: '/roles' },
  { title: props.role.name, href: `/roles/${props.role.id}` }
];
</script>

<template>
  <Head title="Role Details" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 max-w-xl mx-auto">
      <h1 class="text-2xl font-bold mb-4">Role Details</h1>
      <div><strong>Name:</strong> {{ props.role.name }}</div>
      <div><strong>Permissions:</strong>
        <span v-for="perm in props.role.permissions" :key="perm.id" class="badge bg-secondary me-1">{{ perm.name }}</span>
      </div>
    </div>
  </AppLayout>
</template> 