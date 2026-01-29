<script setup>
  import { ref } from 'vue';
  import FormularioLogin from '../components/FormularioLogin.vue';
  import FormularioRegistro from '../components/FormularioRegistro.vue';
  const mostrarLogin = ref(true);
  const mostrarRegistro = ref(false);
  const titulo = ref("Iniciar Sesión");

  function cambioLogin(){
    mostrarLogin.value = true;
    mostrarRegistro.value = false;
    titulo.value = "Iniciar Sesión";
  }
  function cambioRegistro(){
    mostrarLogin.value = false;
    mostrarRegistro.value = true;
    titulo.value = "Registro";
  }
</script>

<template>
  
  <div class="w-full h-full flex items-center justify-center">
    <div class="login xl:w-2/5 md:w-2/3 sm:w-4/6 bg-[rgba(222,26,88,0.92)] rounded-3xl shadow-2xl p-10 my-10 flex flex-col items-center backdrop-blur-sm">
      <h2 class="text-[rgba(244,179,66,1)] text-3xl font-bold mb-8 tracking-wide">{{ titulo }}</h2>
      <div class="contenedorBotones flex w-full md:w-1/2 bg-white/90 rounded-t-xl shadow mb-8 overflow-hidden">
        <button
          @click="cambioLogin"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent text-[rgba(222,26,88,1)] bg-pink-200 hover:bg-pink-100 hover:text-pink-700 router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >Logearse
        </button>
        <button
          @click="cambioRegistro"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent text-[rgba(222,26,88,1)] bg-pink-200 hover:bg-pink-100 hover:text-pink-700 router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >Registrarse
        </button>
      </div>
        <FormularioLogin v-if="mostrarLogin"></FormularioLogin>
        <FormularioRegistro v-if="mostrarRegistro"></FormularioRegistro>
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