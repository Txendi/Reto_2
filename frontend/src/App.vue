<script setup>
import headerHecho from './components/Header.vue'
import footerHecho from '../src/components/Footer.vue'
import { useUserStore } from './stores/userStore';

import { useRoute } from 'vue-router' // Importa useRoute

const route = useRoute(); // Obtén la ruta actual

async function prueba(){

  const userStore = useUserStore();
  console.log(userStore.isAuthenticated);
  console.log(userStore.isAdmin);
  userStore.user = {role: "user", id: 2, username: 21332, email: "asereje"};
  userStore.status = "authenticated";
  console.log(userStore.isAuthenticated);
  console.log(userStore.isAdmin);

}

// onMounted(
//   prueba
// )

</script>

<template>
  <div class="contenedorPadre relative min-h-screen flex flex-col overflow-hidden">
    <headerHecho class="relative z-20" />
    <div class="relative flex-1 flex items-stretch z-10 h-full">
      <!-- Solo muestra el video en las rutas de login o registro -->
      <video
        v-if="route.name === 'login'"
        autoplay
        muted
        loop
        playsinline
        class="absolute inset-0 w-full h-full object-cover z-0"
      >
        <source src="../src/img/ia-slop.mp4" type="video/mp4" />
      </video>

      <div class="relative w-full z-10">
        <RouterView />
      </div>
    </div>
    <footerHecho class="relative z-20" />
  </div>
</template>

<style scoped>
.contenedorPadre .central-video-bg::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.25);
  z-index: 1;
  pointer-events: none;
}
</style>