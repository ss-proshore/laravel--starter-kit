<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Table, TableHeader, TableBody, TableRow, TableCell, TableHead } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ permissions: any, search?: string }>();
const breadcrumbs = [
  { title: 'Permissions', href: '/permissions' }
];
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
const searchInput = ref(props.search || '');
function searchPermissions() {
  router.get('/permissions', { search: searchInput.value }, { preserveState: true, replace: true });
}
function clearSearch() {
  searchInput.value = '';
  router.get('/permissions', {}, { preserveState: true, replace: true });
}
function destroy(id: number) {
  if (confirm('Are you sure?')) {
    router.delete(`/permissions/${id}`);
  }
}
</script>

<template>
  <Head title="Permissions" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-6 h-full">
      <Card class="w-full">
        <CardHeader>
          <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <CardTitle>Permissions</CardTitle>
            <Button as="a" href="/permissions/create">
              Create Permission
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          <!-- Search Field -->
          <form @submit.prevent="searchPermissions" class="mb-6 flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
            <Input v-model="searchInput" placeholder="Search permissions..." class="w-full sm:w-64" />
            <Button type="submit" variant="outline">Search</Button>
            <Button v-if="searchInput" type="button" variant="ghost" @click="clearSearch">Clear</Button>
          </form>

          <div class="overflow-x-auto">
            <Table class="w-full">
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead class="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="permission in props.permissions?.data || []" :key="permission.id">
                  <TableCell>{{ permission.name }}</TableCell>
                  <TableCell class="text-right">
                    <Button as="a" :href="`/permissions/${permission.id}/edit`" size="sm" variant="outline" class="mr-2">
                      Edit
                    </Button>
                    <Button @click="destroy(permission.id)" size="sm" variant="destructive">
                      Delete
                    </Button>
                  </TableCell>
                </TableRow>
                 <TableRow v-if="!props.permissions?.data || props.permissions.data.length === 0">
                  <TableCell colspan="2" class="text-center">No permissions found.</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>

          <!-- Pagination Controls -->
          <div v-if="props.permissions?.links?.length > 3" class="flex justify-center mt-4 gap-2 flex-wrap">
            <Button
              v-for="link in props.permissions.links"
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