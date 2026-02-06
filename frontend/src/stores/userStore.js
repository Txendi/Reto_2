//import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: {
      id: null,
      email: null,
      username: null,
      rol: null,
    }, // datos del usuario (o null)
    status: 'unknown', // 'unknown' | 'authenticated' | 'guest'
  }),

  getters: {
    isAuthenticated: (state) => state.status === 'authenticated',
    isAdmin: (state) => state.user?.role === 'admin',
    username: (state) => state.user?.username || '',
  },

  actions: {
    async fetchAuthState() {
      //Evita repetir llamadas si ya sabemos el estado
      return {
        isAuthenticated: this.isAuthenticated,
        isAdmin: this.isAdmin,
      }
    },
    logout() {
      this.user.id = null
      this.user.email = null
      this.user.username = null
      this.user.rol = null
      this.status = 'unknown'
      /* window.location.href = 'http://localhost:5173/login' */
    },
  },

})
