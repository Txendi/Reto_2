<script setup></script>

<template>
  <header>
    <nav
      class="contenedorPrincipal flex items-center justify-between px-8 py-3 shadow-lg rounded-b-2xl"
    >
      <div class="flex items-center space-x-4">
        <p class="text-2xl font-extrabold text-white drop-shadow-lg">Placeholder Logo</p>
      </div>
      <div class="flex items-center space-x-8">
        <router-link
          to="/eventos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
          >Eventos</router-link
        >
        <router-link
          to="/juegos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
          >Juegos</router-link
        >
        <router-link
          to="/login"
          class="botonRegistrarse ml-4 px-6 py-2 rounded-full font-bold text-[rgba(222,26,88,1)] bg-[rgba(244,179,66,1)] border-2 border-[rgba(222,26,88,1)] shadow-lg transition-all duration-500 focus:outline-none focus:shadow-[0_0_0_3px_rgba(222,26,88,0.33)]"
        >
          Registrarse / Iniciar sesión
        </router-link>
      </div>
    </nav>
  </header>
</template>

<style scoped>
.contenedorPrincipal {
  background: linear-gradient(
    90deg,
    rgba(244, 179, 66, 1) 0%,
    rgba(222, 26, 88, 1) 40%,
    rgba(54, 1, 133, 1) 100%
  );
}

.textoHeader::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -1px;
  width: 0;
  height: 3px;
  background: linear-gradient(90deg, #f4b342 0%, #de1a58 100%);
  border-radius: 2px;
  transition: width 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.textoHeader:hover::after {
  width: 100%;
}

.botonRegistrarse:hover {
  background-color: #de1a58 !important;
  color: #f4b342 !important;
  border-color: #f4b342 !important;
  transform: scale(1.15);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
  transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
</style>
