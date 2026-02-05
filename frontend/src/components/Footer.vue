<script setup></script>

<template>
  <footer class="contenedorFooter w-screen">
    <div class="flex items-center justify-between px-8 py-4">

      <p class="text-white/80 text-1xl font-medium">© 2026 Placeholder Logo</p>


      <div class="flex items-center gap-1">
        <a href="https://www.instagram.com/" class="enlaceFooter text-white font-semibold text-lg transition-colors duration-300"><img
            src="../img/icons8-instagram-50.png" class="logo w-1/2" alt="" /></a>
        <a href="https://x.com/?lang=es" class="enlaceFooter text-white font-semibold text-lg transition-colors duration-300"><img
            src="../img/icons8-twitter-50.png" class="logo w-1/2" alt="" /></a>
        <a href="https://es.linkedin.com/?original_referer=https%3A%2F%2Fsearch.brave.com%2F" class="enlaceFooter text-white font-semibold text-lg transition-colors duration-300"><img
            src="../img/icons8-linkedin-50.png" class="logo w-1/2" alt="" /></a>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.contenedorFooter {
  /* Mantenemos tu gradiente que es muy chulo */
  background: linear-gradient(90deg,
      rgba(54, 1, 133, 1) 0%,
      rgba(222, 26, 88, 1) 60%,
      rgba(244, 179, 66, 1) 100%);
  /* Sombra superior para separar del contenido */
  box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.3);
}

.logo {
  filter: brightness(0) invert(1);
}

.enlaceFooter {
  position: relative;
  display: inline-block;
}

.enlaceFooter::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -2px;
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, #f4b342 0%, #de1a58 100%);
  border-radius: 2px;
  transition: width 0.3s ease;
}

.enlaceFooter:hover::after {
  width: 100%;
}
</style>
