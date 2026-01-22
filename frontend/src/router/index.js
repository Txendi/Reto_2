import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import JuegosView from '../views/JuegosView.vue'
import LoginView from'../views/LoginView.vue'
import EventosView from '../views/EventosView.vue'
import FormularioLogin from '../views/FormularioLogin.vue'
import FormularioRegistro from '../views/FormularioRegistro.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/juegos',
      name: 'juegos',
      component: JuegosView
    },
    {
      path: '/eventos',
      name: 'eventos',
      component: EventosView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/Formulariologin',
      name: 'Formulariologin',
      component: FormularioLogin
    },
    {
      path: '/FormularioRegistro',
      name: 'FormularioRegistro',
      component: FormularioRegistro
    }
  ]
})

export default router
