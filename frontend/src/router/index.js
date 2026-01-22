import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import JuegosView from '../views/JuegosView.vue'
import EventosView from '../views/EventosView.vue'

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
    }
  ]
})

export default router
