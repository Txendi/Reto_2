//import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

function getCookie(cname) {
  var name = cname + "="; //Crea una variable con el texto del campo y un  igual
  var ca = document.cookie.split(';'); //divide la cookie en trozos separados  por ;
  for(let i=0; i<ca.length; i++) { //Recorre cada trozo de la cookie  
    let c = ca[i].trim(); //Extraemos la cookie y eliminamos el espacio inicial
    if (c.indexOf(name) == 0) //Si encuentra el campo buscado
      return c.substring(name.length,c.length); //Lo devuelve
    }
  return ""; //Si no devuelve vacío
}


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

