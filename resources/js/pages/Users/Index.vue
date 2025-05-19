<template>
  <Head title="Users" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
      <Card class="w-full max-w-4xl">
        <CardHeader>
          <div class="flex justify-between items-center">
            <CardTitle>Users</CardTitle>
            <Button as="a" href="/users/create">Create User</Button>
          </div>
        </CardHeader>
        <CardContent>
          <!-- Search Field -->
          <form @submit.prevent="searchUsers" class="mb-4 flex gap-2">
            <Input v-model="searchInput" placeholder="Search users..." class="w-64" />
            <Button type="submit" variant="outline">Search</Button>
            <Button v-if="searchInput" type="button" variant="ghost" @click="clearSearch">Clear</Button>
          </form>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Name</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Roles</TableHead>
                <TableHead>Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="user in props.users.data || []" :key="user.id">
                <TableCell>{{ user.name }}</TableCell>
                <TableCell>{{ user.email }}</TableCell>
                <TableCell>
                  <span v-for="role in user.roles" :key="role.id" class="badge bg-secondary me-1">{{ role.name }}</span>
                </TableCell>
                <TableCell>
                  <Button as="a" :href="`/users/${user.id}/edit`" size="sm" variant="outline">Edit</Button>
                  <Button @click="destroy(user.id)" size="sm" variant="destructive" class="ml-2">Delete</Button>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
          <!-- Pagination Controls -->
          <div class="flex justify-center mt-4 gap-2">
            <Button
              v-for="link in props.users.links"
              :key="link.label"
              :disabled="!link.url"
              :variant="link.active ? 'default' : 'outline'"
              @click="link.url && router.visit(link.url)"
              v-html="link.label"
              size="sm"
            />
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Table, TableHeader, TableBody, TableRow, TableCell, TableHead } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ users: any, search?: string }>();
const breadcrumbs = [{ title: 'Users', href: '/users' }];
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
const searchInput = ref(props.search || '');
function searchUsers() {
  router.get('/users', { search: searchInput.value }, { preserveState: true, replace: true });
}
function clearSearch() {
  searchInput.value = '';
  router.get('/users', {}, { preserveState: true, replace: true });
}
function destroy(id: number) {
  if (confirm('Are you sure?')) {
    router.delete(`/users/${id}`);
  }
}
</script> 