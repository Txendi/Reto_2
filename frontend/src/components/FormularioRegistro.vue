<script setup>
import { ref } from 'vue'
import { useUserStore } from '../stores/userStore.js'

const nombreUsuario = ref('')
const contraUsuario = ref('')
const emailUsuario = ref('')

const error = ref('')


async function registrarUsuario(id, username, email, rol) {
  const userStore = useUserStore();
  userStore.user = { id: id, username: username, email: email, rol: rol };
  userStore.status = "authenticated";
  
}


function validarEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
}

async function registrar() {
  error.value = ''

  if (!validarEmail(emailUsuario.value)) {
    error.value = 'Email inválido'
    return
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}auth/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        usuario: nombreUsuario.value,
        password: contraUsuario.value,
        email: emailUsuario.value,
      }),
    })

    // Leemos el JSON una sola vez
    const data = await response.json();

    if (!response.ok) {
      // Si el PHP devolvió un error (400, 409, 500), lo mostramos
      error.value = data.debug || "Error en el servidor";
      if (data.errors && data.errors.length > 0) {
        error.value += ': ' + data.errors.join(', ');
      }
      return;
    }

    // Si todo fue bien, procesamos los datos
    console.log("=== REGISTRO EXITOSO ===");
    console.log("Nuevo usuario:", data.nuevoUsuario);
    console.log("=== LISTA DE USUARIOS ===");
    console.table(data.listaUsuarios);

    // Guardamos los datos del usuario
    const usuario = data.nuevoUsuario;
 

    // Registramos en el store
    registrarUsuario(usuario.id, usuario.username,usuario.contra, usuario.email, usuario.role);

  } catch (err) {
    console.error('❌ Error conexión:', err);
    error.value = 'Error de conexión con el servidor';
  }
}

</script>

<template>
  <form
    class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 -mt-8 flex flex-col gap-6 backdrop-blur"
    method="GET"
  >
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>

    <div>
      <label for="usuario" class="block text-gray-700 font-semibold mb-2">Usuario</label>
      <input
        type="text"
        id="usuario"
        name="usuario"
        class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
        placeholder="Introduzca nombre de usuario..."
        v-model="nombreUsuario"
      />
    </div>
    <div>
      <label for="contraseña" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
      <input
        type="password"
        name="contraseña"
        id="contraseña"
        class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
        placeholder="Introduzca su contraseña..."
        v-model="contraUsuario"
      />
      <label for="email" class="block text-gray-700 font-semibold mb-2 mt-4">Email</label>
      <input
        type="text"
        id="email"
        name="email"
        class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
        placeholder="Introduzca su email..."
        v-model="emailUsuario"
      />
    </div>

    <button
      type="submit"
      class="mt-4 bg-[rgba(222,26,88,1)] hover:bg-pink-700 text-[rgba(244,179,66,1)] font-bold py-3 rounded-xl shadow transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:shadow-pink-300/60 active:scale-95"
      @click.prevent="registrar"
    >
      Registrarse
    </button>

  </form>
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