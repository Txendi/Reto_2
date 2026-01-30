import { createRouter, createWebHistory } from 'vue-router'
import JuegosView from '../views/JuegosView.vue'
import LoginView from'../views/LoginView.vue'
import EventosView from '../views/EventosView.vue'
import PerfilView from '../views/PerfilView.vue'
import { useUserStore } from "../stores/userStore.js"


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: JuegosView,
      meta: {requiresAuth: false}
    },
    {
      path: '/juegos',
      name: 'juegos',
      component: JuegosView,
      meta: {requiresAuth: false}
    },
    {
      path: '/eventos',
      name: 'eventos',
      component: EventosView,
      meta: {requiresAuth: false}
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {requiresAuth: false}
    },
    {
      path: '/perfil',
      name: 'perfil',
      component: PerfilView,
      meta: {requiresAuth: true}
    }
  ]
});
router.beforeEach(async (to) => {
  const userStore = useUserStore();
  const { isAuthenticated, isAdmin } = await userStore.fetchAuthState();
  console.log(isAuthenticated)

  if (to.meta.requiresAuth && !isAuthenticated) {
    return { name: 'login' }
  }
  if (to.meta.requiresAdmin && !isAdmin) { 
    return { name: 'home' }
  }

  return true
})


export default router
