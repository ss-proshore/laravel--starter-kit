import { defineStore } from 'pinia';
import type { User } from '@/types';

export const useUserStore = defineStore('userStore', {
    state: () => ({
        user: null as User | null,
    }),

    actions: {
        setUser(userData: User | null) {
            this.user = userData;
        },
        clearUser() {
            this.user = null;
        },
        async fetchUser() {
            try {
                const response = await fetch('/user');
                const userData = await response.json();
                this.setUser(userData);
            } catch (error) {
                console.error('Failed to fetch user:', error);
                this.clearUser();
            }
        },
    },

    getters: {
        getUser: (state) => state.user,
        isAuthenticated: (state) => !!state.user,
    },

    // Enable persistence
    persist: {
        enabled: true,
        strategies: [
            {
                key: 'user-store',
                storage: localStorage, // or sessionStorage
            },
        ],
    },
});
