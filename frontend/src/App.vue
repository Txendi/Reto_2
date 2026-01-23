<script setup>
import headerHecho from '../src/components/header.vue'
import footerHecho from '../src/components/Footer.vue'
import { RouterLink, RouterView } from 'vue-router'

function prueba() {
  fetch('http://localhost/bbdd.php')
    .then((response) => response.json())
    .then((data) => {
      console.log('Datos JSON:', data)
    })
    .catch((error) => {
      console.error('Error en la petición:', error)
    })
}
</script>

<template>
  <div class="contenedorPadre relative min-h-screen flex flex-col overflow-hidden">
    <headerHecho class="relative z-20" />

    <div class="relative flex-1 flex items-stretch z-10">
      <video
        autoplay
        muted
        loop
        playsinline
        class="absolute inset-0 w-full h-full object-cover z-0"
      >
        <source src="../src/img/ia-slop.mp4" type="video/mp4" />
        Tu navegador no soporta el vídeo de fondo.
      </video>

      <div class="relative w-full z-10">
        <RouterView />
      </div>
    </div>

    <footerHecho class="relative z-20" />
  </div>
</template>

<style scoped>
/* Opcional: oscurecer el vídeo para mejorar la legibilidad */
.contenedorPadre .central-video-bg::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.25);
  z-index: 1;
  pointer-events: none;
}
</style>
