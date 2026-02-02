//import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,              // datos del usuario (o null)
    status: 'unknown',       // 'unknown' | 'authenticated' | 'guest'
  }),

  getters: {
    isAuthenticated: (state) => state.status === 'authenticated',
    isAdmin: (state) => state.user?.role === 'admin',
  },

  actions: {
    async fetchAuthState() {
      //Evita repetir llamadas si ya sabemos el estado
        return {
          isAuthenticated: this.isAuthenticated,
          isAdmin: this.isAdmin,
        }
      }
    }
  }
)

