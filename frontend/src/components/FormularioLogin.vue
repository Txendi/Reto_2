<script setup>
import { ref, onMounted } from 'vue'
import { useUserStore,  } from '../stores/userStore'
const nombreUsuario = ref('')
const contraUsuario = ref('')
const mensaje = ref('')
const error = ref('')
const userStore = useUserStore()

onMounted(() => {
  // if (userStore.isAuthenticated && userStore.user) {
    console.log("Hola " + userStore.username)
  // }
})
async function loginUsuario(username, contrasena) {
  const userStore = useUserStore()
  userStore.user = { username: username, contrasena: contrasena }
  userStore.status = 'authenticated'
}



async function login() {
  try {
    const response = await fetch('http://localhost/auth/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        usuario: nombreUsuario.value,
        password: contraUsuario.value,
      }),
    })

    // Leemos el JSON una sola vez
    const data = await response.json()

    // 3. Procesamos la lógica con el objeto 'data' ya obtenido
    if (response.ok && data.status === 'ok') {
      console.log('✅ Login correcto:', data)
      mensaje.value = data.debug || 'Login correcto'
      error.value = ''

      // Acceder a usuarioLogeado (no nuevoUsuario) y DENTRO del if
      const usuario = data.usuarioLogeado
      if (usuario && usuario.username) {
        loginUsuario(usuario.username, contraUsuario.value)
      }

      nombreUsuario.value = ''
      contraUsuario.value = ''
    } else {
      console.log('❌ Login fallido:', data.debug)
      error.value = data.errors ? data.errors.join(', ') : 'Error en el login'
      mensaje.value = ''
    }
  } catch (err) {
    console.error('❌ Error conexión:', err)
    error.value = 'Error de conexión con el servidor'
    mensaje.value = ''
  }
}
</script>

<template>
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

    <!-- Mostrar mensajes de error -->
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
      {{ error }}
    </div>

    <!-- Mostrar mensajes de éxito -->
    <div
      v-if="mensaje"
      class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl"
    >
      {{ mensaje }}
    </div>

    <button
      type="submit"
      class="mt-4 bg-[rgba(222,26,88,1)] hover:bg-pink-700 text-[rgba(244,179,66,1)] font-bold py-3 rounded-xl shadow transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:shadow-pink-300/60 active:scale-95"
      @click.prevent="login"
    >
      Entrar
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