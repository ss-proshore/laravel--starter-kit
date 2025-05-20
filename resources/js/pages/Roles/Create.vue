<template>
  <Head title="Create Role" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-6 h-full">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
        <!-- Left Side - Role Details -->
        <div class="lg:col-span-4">
          <Card>
            <CardHeader>
              <CardTitle>Role Details</CardTitle>
              <CardDescription>Enter the basic information for the new role.</CardDescription>
            </CardHeader>
            <CardContent>
              <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-2">
                  <Label for="name">Role Name</Label>
                  <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    placeholder="Enter role name"
                    class="w-full"
                  />
                </div>
              </form>
            </CardContent>
          </Card>
        </div>

        <!-- Right Side - Permissions -->
        <div class="lg:col-span-8">
          <Card class="h-full">
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-4">
              <div>
                <CardTitle>Permissions</CardTitle>
                <CardDescription>Assign permissions to this role.</CardDescription>
              </div>
              <Button
                variant="outline"
                size="sm"
                v-if="checkIfHasPermission(['create roles'])"
                @click="toggleAllPermissions"
                class="h-8"
              >
                {{ areAllPermissionsSelected ? 'Deselect All' : 'Select All' }}
              </Button>
            </CardHeader>
            <CardContent>
              <div class="space-y-6">
                <!-- Permission Groups -->
                <div v-for="group in props.groupedPermissions" :key="group.module" class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div class="space-y-1">
                      <h3 class="text-sm font-semibold text-foreground">{{ group.module }}</h3>
                      <p class="text-sm text-muted-foreground">
                        {{ group.permissions.length }} permissions
                      </p>
                    </div>
                    <Button
                      variant="ghost"
                      size="sm"
                      @click="toggleGroupPermissions(group)"
                      class="h-8"
                    >
                      {{ isGroupSelected(group) ? 'Deselect All' : 'Select All' }}
                    </Button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                      v-for="permission in group.permissions"
                      :key="permission.id"
                      class="flex items-center space-x-3 rounded-lg border p-4 hover:bg-accent/50 transition-colors"
                    >
                      <Checkbox
                        :id="'permission-' + permission.id"
                        :model-value="form.permissions.includes(permission.id)"
                        @update:model-value="(checked) => handlePermissionChange(permission.id, checked)"
                        class="h-5 w-5"
                      />
                      <Label
                        :for="'permission-' + permission.id"
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                      >
                        {{ formatPermissionName(permission.name) }}
                      </Label>
                    </div>
                  </div>
                  <Separator class="my-4" />
                </div>
              </div>
            </CardContent>
            <CardContent class="flex justify-end space-x-3 pt-4 border-t">
              <Button
                variant="outline"
                @click="router.visit('/roles')"
              >
                Cancel
              </Button>
              <Button
                type="submit"
                @click="submit"
              >
                Create Role
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Separator } from '@/components/ui/separator';

import { checkIfHasPermission } from '@/composables/usePermission';

interface Permission {
  id: number;
  name: string;
}

interface PermissionGroup {
  module: string;
  permissions: Permission[];
}

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ groupedPermissions: PermissionGroup[] }>();

const breadcrumbs = [
  { title: 'Roles', href: '/roles' },
  { title: 'Create', href: '/roles/create' }
];

const form = ref({ name: '', permissions: [] as number[] });

// Format permission name for display (e.g., "view users" -> "View Users")
const formatPermissionName = (name: string) => {
  return name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};

// Check if all permissions in a group are selected
const isGroupSelected = (group: PermissionGroup) => {
  return group.permissions.every(permission =>
    form.value.permissions.includes(permission.id)
  );
};

// Check if all permissions are selected
const areAllPermissionsSelected = computed(() => {
  const allPermissions = props.groupedPermissions.flatMap(group => group.permissions);
  return allPermissions.every(permission => form.value.permissions.includes(permission.id));
});

// Toggle all permissions
const toggleAllPermissions = () => {
  const allPermissions = props.groupedPermissions.flatMap(group => group.permissions);
  if (areAllPermissionsSelected.value) {
    form.value.permissions = [];
  } else {
    form.value.permissions = allPermissions.map(p => p.id);
  }
};

// Toggle all permissions in a group
const toggleGroupPermissions = (group: PermissionGroup) => {
  const allSelected = isGroupSelected(group);
  const permissionIds = group.permissions.map(p => p.id);

  if (allSelected) {
    // Remove all permissions from this group
    form.value.permissions = form.value.permissions.filter(
      (id: number) => !permissionIds.includes(id)
    );
  } else {
    // Add all permissions from this group
    const newPermissions = [...form.value.permissions];
    permissionIds.forEach(id => {
      if (!newPermissions.includes(id)) {
        newPermissions.push(id);
      }
    });
    form.value.permissions = newPermissions;
  }
};

// Handle individual permission checkbox change
const handlePermissionChange = (permissionId: number, checked: boolean): void => {
  if (checked) {
    // Add permission ID if checked and not already in the array
    if (!form.value.permissions.includes(permissionId)) {
      form.value.permissions.push(permissionId);
    }
  } else {
    // Remove permission ID if unchecked
    form.value.permissions = form.value.permissions.filter(id => id !== permissionId);
  }
};

function submit() {
  router.post('/roles', form.value);
}
</script>
