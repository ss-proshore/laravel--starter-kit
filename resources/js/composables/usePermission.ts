import { computed } from 'vue';
import { useUserStore } from '@/stores/users';
import _ from 'lodash';

export function checkIfHasPermission(requiredPermissions: string[]) {
    const userStore = useUserStore();
    const hasPermission = computed(() => {
        const user = userStore.user;

        if (!user || !_.isArray(user.permissions)) {
            return false;
        }
        return _.intersection(user.permissions, requiredPermissions).length > 0;
    });

    return hasPermission.value;
}
