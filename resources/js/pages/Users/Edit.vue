<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { FormField, FormItem, FormLabel, FormControl, FormMessage, Form } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter, CardDescription } from '@/components/ui/card';

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

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ user: User, roles: Role[] }>();
console.log('Full user data:', JSON.stringify(props.user, null, 2));
console.log('User roles from props:', JSON.stringify(props.user.roles, null, 2));
console.log('Available roles:', JSON.stringify(props.roles, null, 2));
const breadcrumbs = [
  { title: 'Users', href: '/users' },
  { title: 'Edit', href: `/users/${props.user.id}/edit` }
];
const form = ref({
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: '',
  roles: props.user.roles.map(r => r.id) as number[],
});
console.log('Initial form roles:', form.value.roles);

// Create a computed property for each role's checked state
const roleBindings = computed(() => {
  const bindings: Record<number, { checked: boolean }> = {};
  (props.roles || []).forEach((role: Role) => {
    bindings[role.id] = {
      checked: form.value.roles.includes(role.id)
    };
  });
  return bindings;
});

const handleChange = (roleId: number): void => {
  const isCurrentlyChecked = form.value.roles.includes(roleId);
  
  if (isCurrentlyChecked) {
    form.value.roles = form.value.roles.filter((id: number) => id !== roleId);
  } else {
    form.value.roles = [...form.value.roles, roleId];
  }
};

function submit(): void {
  const formData = {
    ...form.value,
    // Only include password fields if they are not empty
    password: form.value.password || undefined,
    password_confirmation: form.value.password_confirmation || undefined,
    roles: Array.isArray(form.value.roles) ? form.value.roles : []
  };
  
  // Remove password fields if they are undefined (empty)
  if (formData.password === undefined) delete formData.password;
  if (formData.password_confirmation === undefined) delete formData.password_confirmation;

  console.log('Submitting form data:', formData);
  
  router.patch(`/users/${props.user.id}`, formData, {
    onSuccess: () => {
      console.log('User updated successfully');
    },
    onError: (errors: any) => {
      console.error('Error updating user:', errors);
    }
  });
}
</script>

<template>
  <Head title="Edit User" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-6 h-full">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
        <!-- Left Side - User Details -->
        <div class="lg:col-span-4">
          <Card>
            <CardHeader>
              <CardTitle>User Details</CardTitle>
              <CardDescription>Edit the basic information for the user.</CardDescription>
            </CardHeader>
            <CardContent>
              <form @submit.prevent="submit" class="space-y-6">
                <FormField name="name">
                  <FormLabel>Name</FormLabel>
                  <FormControl>
                    <Input v-model="form.name" type="text" required />
                  </FormControl>
                  <FormMessage />
                </FormField>
                <FormField name="email">
                  <FormLabel>Email</FormLabel>
                  <FormControl>
                    <Input v-model="form.email" type="email" required />
                  </FormControl>
                  <FormMessage />
                </FormField>
                <FormField name="password">
                  <FormLabel>Password (leave blank to keep current)</FormLabel>
                  <FormControl>
                    <Input v-model="form.password" type="password" />
                  </FormControl>
                  <FormMessage />
                </FormField>
                <FormField name="password_confirmation">
                  <FormLabel>Confirm Password</FormLabel>
                  <FormControl>
                    <Input v-model="form.password_confirmation" type="password" />
                  </FormControl>
                  <FormMessage />
                </FormField>
              </form>
            </CardContent>
          </Card>
        </div>

        <!-- Right Side - Roles -->
        <div class="lg:col-span-8">
          <Card class="h-full">
            <CardHeader>
              <CardTitle>Assign Roles</CardTitle>
              <CardDescription>Select the roles for this user.</CardDescription>
            </CardHeader>
            <CardContent>
              <FormField name="roles">
                <FormLabel class="sr-only">Roles</FormLabel>
                <FormControl>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <FormItem v-for="role in props.roles || []" :key="role.id" class="flex items-center space-x-3 rounded-lg border p-4 hover:bg-accent/50 transition-colors">
                      <FormControl>
                         <Checkbox 
                          :id="'role-' + role.id"
                          :model-value="roleBindings[role.id]?.checked || false"
                          @update:model-value="() => handleChange(role.id)"
                          class="h-5 w-5"
                        />
                      </FormControl>
                      <FormLabel :for="'role-' + role.id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        {{ role.name }}
                      </FormLabel>
                    </FormItem>
                  </div>
                </FormControl>
                <FormMessage />
              </FormField>
            </CardContent>
            <CardFooter class="flex justify-end space-x-3 pt-4 border-t">
              <Button
                variant="outline"
                @click="router.visit('/users')"
              >
                Cancel
              </Button>
              <Button
                type="submit"
                @click.prevent="submit"
              >
                Update User
              </Button>
            </CardFooter>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template> 