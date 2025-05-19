<template>
  <Head title="Create User" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="container mx-auto p-6 h-full">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
        <!-- Left Side - User Details -->
        <div class="lg:col-span-4">
          <Card>
            <CardHeader>
              <CardTitle>User Details</CardTitle>
              <CardDescription>Enter the basic information for the new user.</CardDescription>
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
                  <FormLabel>Password</FormLabel>
                  <FormControl>
                    <Input v-model="form.password" type="password" required />
                  </FormControl>
                  <FormMessage />
                </FormField>
                <FormField name="password_confirmation">
                  <FormLabel>Confirm Password</FormLabel>
                  <FormControl>
                    <Input v-model="form.password_confirmation" type="password" required />
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
                Create User
              </Button>
            </CardFooter>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

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

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ roles: Role[] }>();

const breadcrumbs = [
  { title: 'Users', href: '/users' },
  { title: 'Create', href: '/users/create' }
];

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  roles: [] as number[],
});

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
    roles: Array.isArray(form.value.roles) ? form.value.roles : []
  };
  
  console.log('Submitting form data:', formData);
  
  router.post('/users', formData, {
    onSuccess: () => {
      console.log('User created successfully');
    },
    onError: (errors: any) => {
      console.error('Error creating user:', errors);
    }
  });
}
</script> 