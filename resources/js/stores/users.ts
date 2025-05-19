import { defineStore } from 'pinia';
import type { User } from '@/types'; // Assuming your User type is in @/types

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as User | null,
  }),
  actions: {
    // Action to set the user data
    setUser(userData: User | null) {
      this.user = userData;
    },

    // Optional: Action to clear user data (e.g., on logout)
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
    getUser(state) {
      return state.user;
    },
    isAuthenticated(state) {
      return !!state.user;
    },
  },
},
{
    persist: true,
});
