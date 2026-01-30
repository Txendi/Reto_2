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
      if (this.status !== 'unknown') {
        return {
          isAuthenticated: this.isAuthenticated,
          isAdmin: this.isAdmin,
        }
      } else {
          let name = "usuario=";
          let ca = document.cookie.split(';');
          for(let i = 0; i <ca.length; i++) {
            let c = ca[i].trim();
            
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
      }
      try {
        const res = await fetch("http://localhost/user");
        res
      }catch(e){
        console.log(e);
      }
    }
  }
}
)

