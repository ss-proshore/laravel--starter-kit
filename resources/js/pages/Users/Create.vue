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
              <form @submit.prevent="onSubmit" class="space-y-6">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem>
                    <FormLabel>Name</FormLabel>
                    <FormControl>
                      <Input v-bind="componentField" type="text" required />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="email">
                  <FormItem>
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                      <Input v-bind="componentField" type="email" required />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="password">
                  <FormItem>
                    <FormLabel>Password</FormLabel>
                    <FormControl>
                      <Input v-bind="componentField" type="password" required />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="password_confirmation">
                  <FormItem>
                    <FormLabel>Confirm Password</FormLabel>
                    <FormControl>
                      <Input v-bind="componentField" type="password" required />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
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
              <FormField v-slot="{ componentField }" name="roles">
                <FormItem>
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
                </FormItem>
              </FormField>
            </CardContent>
            <CardFooter class="flex justify-end space-x-3 pt-4 border-t">
              <Button
                variant="outline"
                @click="router.visit('/users')"
                type="button"
              >
                Cancel
              </Button>
              <Button
                type="submit"
                @click.prevent="onSubmit"
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
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { toast } from 'vue-sonner'
import * as z from 'zod';

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

const formSchema = toTypedSchema(z.object({
  name: z.string()
    .min(2, { message: 'Name must be at least 2 characters.' })
    .max(50, { message: 'Name must not be longer than 50 characters.' }),
  email: z.string()
    .email({ message: 'Please enter a valid email address.' })
    .min(1, { message: 'Email is required.' }),
  password: z.string()
    .min(8, { message: 'Password must be at least 8 characters.' })
    .regex(/[A-Z]/, { message: 'Password must contain at least one uppercase letter.' })
    .regex(/[a-z]/, { message: 'Password must contain at least one lowercase letter.' })
    .regex(/[0-9]/, { message: 'Password must contain at least one number.' })
    .regex(/[^A-Za-z0-9]/, { message: 'Password must contain at least one special character.' }),
  password_confirmation: z.string()
    .min(1, { message: 'Please confirm your password.' }),
  roles: z.array(z.number()).optional(),
}).refine((data) => data.password === data.password_confirmation, {
  message: "Passwords don't match",
  path: ["password_confirmation"],
}));

const { handleSubmit, values, errors, defineField } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: [] as number[],
  }
});

const [name, nameField] = defineField('name');
const [email, emailField] = defineField('email');
const [password, passwordField] = defineField('password');
const [passwordConfirmation, passwordConfirmationField] = defineField('password_confirmation');
const [roles, rolesField] = defineField('roles');

// Create a computed property for each role's checked state
const roleBindings = computed(() => {
  const bindings: Record<number, { checked: boolean }> = {};
  (props.roles || []).forEach((role: Role) => {
    bindings[role.id] = {
      checked: roles.value.includes(role.id)
    };
  });
  return bindings;
});

const handleChange = (roleId: number): void => {
  const currentRoles = roles.value || [];
  const isCurrentlyChecked = currentRoles.includes(roleId);
  
  if (isCurrentlyChecked) {
    roles.value = currentRoles.filter((id: number) => id !== roleId);
  } else {
    roles.value = [...currentRoles, roleId];
  }
};

const onSubmit = handleSubmit((values) => {
  router.post('/users', values, {
    onSuccess: () => {
      toast.success('User created successfully');
    },
    onError: (errors) => {
      toast.error('Failed to create user. Please check the form for errors.');
    }
  });
});
</script> 