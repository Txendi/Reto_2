<script setup></script>

<template>
  <div class="w-full h-full flex items-center justify-center bg-gradient-to-b from-pink-100 to-white">
    <div class="login xl:w-2/5 md:w-2/3 sm:w-4/6 bg-[rgba(222,26,88,1)] rounded-3xl shadow-2xl p-10 my-10 flex flex-col items-center">
      <h2 class="text-[rgba(244,179,66,1)] text-3xl font-bold mb-8 tracking-wide">Iniciar Sesión</h2>
      <div class="contenedorBotones flex w-1/2 bg-white rounded-t-xl shadow mb-8 overflow-hidden">
        <router-link
          to="/FormularioLogin"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent
            text-[rgba(222,26,88,1)] hover:bg-pink-100 hover:text-pink-700
            router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >
          Logearse
        </router-link>
        <router-link
          to="/FormularioRegistro"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent
            text-[rgba(222,26,88,1)] hover:bg-pink-100 hover:text-pink-700
            router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >
          Registrarse
        </router-link>
      </div>
    
    </div>
  </div>
</template>

<style scoped>

.router-link-exact-active {
  background: #fff !important;
  border-bottom-width: 4px !important;
  border-color: rgba(222,26,88,1) !important;
  color: rgba(222,26,88,1) !important;
  z-index: 10;
}
</style>
