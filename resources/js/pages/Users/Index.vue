<template>
  <Head title="Users" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-6 h-full">
      <Card class="w-full">
        <CardHeader>
          <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <CardTitle>Users</CardTitle>
            <Button as="a" href="/users/create">
              Create User
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          <!-- Search Field -->
          <form @submit.prevent="searchUsers" class="mb-6 flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
            <Input v-model="searchInput" placeholder="Search users by name or email..." class="w-full sm:w-64" />
            <Button type="submit" variant="outline">Search</Button>
            <Button v-if="searchInput" type="button" variant="ghost" @click="clearSearch">Clear</Button>
          </form>
          
          <div class="overflow-x-auto">
            <Table class="w-full">
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Roles</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="user in props.users?.data || []" :key="user.id">
                  <TableCell>{{ user.name }}</TableCell>
                  <TableCell>{{ user.email }}</TableCell>
                  <TableCell>
                    <span v-for="role in user.roles" :key="role.id" class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-700 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-500/10 dark:ring-gray-700/10 me-1">
                      {{ role.name }}
                    </span>
                  </TableCell>
                  <TableCell class="text-right">
                    <Button as="a" :href="`/users/${user.id}/edit`" size="sm" variant="outline" class="mr-2">
                      Edit
                    </Button>
                    <Button @click="destroy(user.id)" size="sm" variant="destructive">
                      Delete
                    </Button>
                  </TableCell>
                </TableRow>
                <TableRow v-if="!props.users?.data || props.users.data.length === 0">
                  <TableCell colspan="4" class="text-center">No users found.</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
          
          <!-- Pagination Controls -->
          <div v-if="props.users?.links?.length > 3" class="flex justify-center mt-4 gap-2 flex-wrap">
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
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Role {
  id: number;
  name: string;
}

interface User {
  id: number;
  name: string;
  email: string;
  roles: Role[];
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface UsersPaginationData {
  data: User[];
  links: PaginationLink[];
  // Add other pagination properties if needed (e.g., current_page, last_page, total, etc.)
}

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ users: UsersPaginationData, search?: string }>();

const breadcrumbs = [{ title: 'Users', href: '/users' }];

const searchInput = ref(props.search || '');

function searchUsers(): void {
  router.get('/users', { search: searchInput.value }, { preserveState: true, replace: true });
}

function clearSearch(): void {
  searchInput.value = '';
  router.get('/users', {}, { preserveState: true, replace: true });
}

function destroy(id: number): void {
  if (confirm('Are you sure?')) {
    router.delete(`/users/${id}`);
  }
}
</script> 