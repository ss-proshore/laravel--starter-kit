import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData, User } from '@/types'; // Assuming User and SharedData types are here

/**
 * Composable to check if the authenticated user has a specific permission.
 * Assumes user permissions are available via Inertia page props (page.props.auth.user.can()).
 *
 * @param {string} permissionName The name of the permission to check.
 * @returns {ComputedRef<boolean>} A computed property that is true if the user has the permission, false otherwise.
 */
export function usePermission(permissionName: string) {
  const page = usePage<SharedData>();

  // Cast user to User type, assuming 'can' method exists on it due to Spatie package integration with Inertia
  const user = page.props.auth.user as User;
console.log(user);

  const hasPermission = computed(() => {
    // Check if user and the can method exist before calling
    if (user && typeof user.can === 'function') {
      return user.can(permissionName);
    }
    // If user or can method is not available, assume no permission
    return false;
  });

  return hasPermission;
} 