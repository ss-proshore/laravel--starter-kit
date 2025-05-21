<template>
    <Head title="Activity logs" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="container mx-auto p-6 h-full">
        <Card class="w-full">
          <CardHeader>
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
              <CardTitle>Activity Logs</CardTitle>
            </div>
          </CardHeader>
          <CardContent>
            <!-- Search Field -->
            <form @submit.prevent="searchLogs" class="mb-6 flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
              <Input v-model="searchInput" placeholder="Search logs by description..." class="w-full sm:w-64" />
              <Button type="submit" variant="outline">Search</Button>
              <Button v-if="searchInput" type="button" variant="ghost" @click="clearSearch">Clear</Button>
            </form>
  
            <div class="overflow-x-auto">
              <Table class="w-full">
                <TableHeader>
                  <TableRow>
                    <TableHead>Description</TableHead>
                    <TableHead>User</TableHead>
                    <TableHead>Event</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Properties</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow v-for="log in props.activityLogs?.data || []" :key="log.id">
                    <TableCell>{{ log.description }}</TableCell>
                    <TableCell>{{ log.causer?.name || 'System' }}</TableCell>
                    <TableCell>
                      <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-700 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-500/10 dark:ring-gray-700/10">
                        {{ log.event || 'N/A' }}
                      </span>
                    </TableCell>
                    <TableCell>{{ formatDate(log.created_at) }}</TableCell>
                    <TableCell>
                      <Button v-if="log.properties" @click="showProperties(log)" size="sm" variant="outline">
                        View Details
                      </Button>
                    </TableCell>
                  </TableRow>
                  <TableRow v-if="!props.activityLogs?.data || props.activityLogs.data.length === 0">
                    <TableCell colspan="5" class="text-center">No activity logs found.</TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </div>
  
            <!-- Pagination Controls -->
            <div v-if="props.activityLogs?.links?.length > 3" class="flex justify-center mt-4 gap-2 flex-wrap">
              <Button
                v-for="link in props.activityLogs.links"
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

      <!-- Properties Modal -->
      <Dialog v-model:open="showModal">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Activity Details</DialogTitle>
          </DialogHeader>
          <div class="mt-4 space-y-4">
            <!-- Causer Information -->
            <div v-if="selectedLog?.causer">
              <h3 class="text-sm font-medium mb-2">User Information</h3>
              <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96">{{ JSON.stringify(selectedLog.causer, null, 2) }}</pre>
            </div>
            
            <!-- Properties -->
            <div v-if="selectedLog?.properties">
              <h3 class="text-sm font-medium mb-2">Additional Details</h3>
              <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96">{{ JSON.stringify(selectedLog.properties, null, 2) }}</pre>
            </div>

            <!-- Raw Log Data -->
            <div>
              <h3 class="text-sm font-medium mb-2">Raw Log Data</h3>
              <pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96">{{ JSON.stringify({
                id: selectedLog?.id,
                description: selectedLog?.description,
                event: selectedLog?.event,
                created_at: selectedLog?.created_at,
                updated_at: selectedLog?.updated_at
              }, null, 2) }}</pre>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </AppLayout>
</template>
  
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Table, TableHeader, TableBody, TableRow, TableCell, TableHead } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Causer {
  id: number;
  name: string;
  email: string;
}

interface ActivityLog {
  id: number;
  description: string;
  causer: Causer | null;
  event: string | null;
  properties: Record<string, any> | null;
  created_at: string;
  updated_at: string;
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface ActivityLogsPaginationData {
  data: ActivityLog[];
  links: PaginationLink[];
}

// @ts-expect-error: defineProps is a macro provided by Vue SFCs
const props = defineProps<{ activityLogs: ActivityLogsPaginationData, search?: string }>();

const breadcrumbs = [{ title: 'Activity Logs', href: '/activity-logs' }];
const searchInput = ref(props.search || '');
const showModal = ref(false);
const selectedLog = ref<ActivityLog | null>(null);

function formatDate(dateString: string): string {
  const date = new Date(dateString);
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  });
}

function searchLogs(): void {
  router.get('/activity-logs', { search: searchInput.value }, { preserveState: true, replace: true });
}

function clearSearch(): void {
  searchInput.value = '';
  router.get('/activity-logs', {}, { preserveState: true, replace: true });
}

function showProperties(log: ActivityLog): void {
  selectedLog.value = log;
  showModal.value = true;
}
</script>
  