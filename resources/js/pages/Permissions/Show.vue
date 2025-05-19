<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ permission: any }>();
const breadcrumbs = [
  { title: "Permissions", href: "/permissions" },
  { title: props.permission.name, href: `/permissions/${props.permission.id}` },
];
</script>

<template>
  <Head title="Permission Details" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="flex flex-col gap-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 max-w-xl mx-auto"
    >
      <h1 class="text-2xl font-bold mb-4">Permission Details</h1>
      <div><strong>Name:</strong> {{ props.permission.name }}</div>
    </div>
  </AppLayout>
</template>
