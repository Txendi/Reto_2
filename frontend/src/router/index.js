import { createRouter, createWebHistory } from 'vue-router'
import JuegosView from '../views/JuegosView.vue'
import LoginView from'../views/LoginView.vue'
import EventosView from '../views/EventosView.vue'
import NuevoEventoView from '../views/NuevoEventoView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: JuegosView
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
      path: '/nuevoEvento',
      name: 'nuevoEvento',
      component: NuevoEventoView
    }
  ]
})

export default router
