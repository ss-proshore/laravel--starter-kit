<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ user: any }>();
const breadcrumbs = [
  { title: 'Users', href: '/users' },
  { title: props.user.name, href: `/users/${props.user.id}` }
];
</script>

<template>
  <Head title="User Details" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
      <Card class="w-full max-w-xl">
        <CardHeader>
          <CardTitle>User Details</CardTitle>
        </CardHeader>
        <CardContent class="flex flex-col gap-4">
          <div><strong>Name:</strong> {{ props.user.name }}</div>
          <div><strong>Email:</strong> {{ props.user.email }}</div>
          <div><strong>Roles:</strong>
            <span v-for="role in props.user.roles" :key="role.id" class="badge bg-secondary me-1">{{ role.name }}</span>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template> 