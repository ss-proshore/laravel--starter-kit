<template>
  <Head title="Edit Permission" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
      <Card class="w-full max-w-xl">
        <CardHeader>
          <CardTitle>Edit Permission</CardTitle>
        </CardHeader>
        <Form class="w-full">
          <CardContent class="flex flex-col gap-4">
            <FormField name="name">
              <FormLabel>Name</FormLabel>
              <FormControl>
                <Input 
                  v-model="form.name" 
                  type="text" 
                  placeholder="Enter permission name"
                  required 
                />
              </FormControl>
              <FormMessage />
            </FormField>
          </CardContent>
          <CardFooter>
            <Button 
              type="submit" 
              class="w-full mt-2" 
              @click.prevent="submit"
            >
              Update Permission
            </Button>
          </CardFooter>
        </Form>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { FormField, FormLabel, FormControl, FormMessage, Form } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ permission: any }>();

const breadcrumbs = [
  { title: 'Permissions', href: '/permissions' },
  { title: 'Edit', href: `/permissions/${props.permission.id}/edit` }
];

const form = ref({
  name: props.permission.name
});

function submit() {
  try {
    console.log('Submitting permission data:', form.value);
    
    router.patch(`/permissions/${props.permission.id}`, form.value, {
      onSuccess: () => {
        console.log('Permission updated successfully');
      },
      onError: (errors: any) => {
        console.error('Error updating permission:', errors);
      }
    });
  } catch (error) {
    console.error('Error in form submission:', error);
  }
}
</script> 