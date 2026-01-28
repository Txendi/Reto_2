<script setup>
import headerHecho from './components/Header.vue'
import footerHecho from '../src/components/Footer.vue'
//import { RouterLink, RouterView } from 'vue-router'

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