
<script setup>
import { ref } from 'vue'
const nombreUsuario = ref('')
const contraUsuario = ref('')

async function login() {  
  try {

  const response = await fetch('http://localhost/bbdd.php?action=logearse&usuario='+nombreUsuario.value+'&contrasena='+contraUsuario.value)
    if (!response.ok) {
      throw new Error('Error HTTP: ' + response.status);
    }
    const data = await response.text();
    console.log('Datos:', data);

  } catch (error) {
    console.error('Error en la petición:', error);
  }



}
</script>

<template>
  <div class="w-full h-full flex items-center justify-center">
    <div
      class="login xl:w-2/5 md:w-2/3 sm:w-4/6 bg-[rgba(222,26,88,0.95)] rounded-3xl shadow-2xl p-10 my-10 flex flex-col items-center"
    >
      <h2 class="text-[rgba(244,179,66,1)] text-3xl font-bold mb-8 tracking-wide">
        Iniciar Sesión
      </h2>
      <div
        class="contenedorBotones flex w-full md:w-1/2 bg-white rounded-t-xl shadow mb-8 overflow-hidden"
      >
        <router-link
          to="/FormularioLogin"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent text-[rgba(222,26,88,1)] hover:bg-pink-100 hover:text-pink-700 router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >
          Logearse
        </router-link>
        <router-link
          to="/FormularioRegistro"
          class="flex-1 text-center px-6 py-2 font-semibold transition-all duration-300 border-b-4 border-transparent text-[rgba(222,26,88,1)] hover:bg-pink-100 hover:text-pink-700 router-link-exact-active:bg-white router-link-exact-active:border-[rgba(222,26,88,1)] router-link-exact-active:text-[rgba(222,26,88,1)]"
        >
          Registrarse
        </router-link>
      </div>

      <form class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 -mt-8 flex flex-col gap-6">
        <div>
          <label for="usuario" class="block text-gray-700 font-semibold mb-2">Usuario</label>
          <input
            type="text"
            id="usuario"
            class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
            placeholder="Introduzca nombre de usuario..."
            v-model="nombreUsuario"
          />
        </div>
        <div>
          <label for="contraseña" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
          <input
            type="password"
            id="contraseña"
            class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
            placeholder="Introduzca su contraseña..."
            v-model="contraUsuario"
          />
        </div>
        <button
          type="submit"
          class="mt-4 bg-[rgba(222,26,88,1)] hover:bg-pink-700 text-[rgba(244,179,66,1)] font-bold py-3 rounded-xl shadow transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:shadow-pink-300/60 active:scale-95"
          
          @click.prevent="login"
          >
          Entrar
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.router-link-exact-active {
  background: #fff !important;
  border-bottom-width: 4px !important;
  border-color: rgba(222, 26, 88, 1) !important;
  color: rgba(222, 26, 88, 1) !important;
  z-index: 10;
}
</style>
