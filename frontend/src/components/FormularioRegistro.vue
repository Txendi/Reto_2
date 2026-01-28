<script setup>
import { ref } from 'vue'
const nombreUsuario = ref('')
const contraUsuario = ref('')
const emailUsuario = ref('')
const mensaje = ref('')
const error = ref('')

function validarEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
}
async function registrar() {
  mensaje.value = ''
  error.value = ''

  // Validación frontend
  if (!validarEmail(emailUsuario.value)) {
    console.error('❌ Email inválido (frontend)')
    error.value = 'Email inválido'
    return
  }

  try {
    const response = await fetch('http://localhost/backend/auth/register', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    usuario: nombreUsuario.value,
    password: contraUsuario.value,
    email: emailUsuario.value,
  })
})

    const data = await response.json()

    if (response.ok && data.status === 'ok') {
      console.log('✅ Registro correcto:', data)
      mensaje.value = data.debug || 'Usuario registrado'
      
      nombreUsuario.value = ''
      contraUsuario.value = ''
      emailUsuario.value = ''
    } else {
      console.error('❌ Error registro:', data)
      error.value = data.debug || 'Error al registrar'
      if (data.errors) {
        console.error('Detalles:', data.errors)
      }
    }

  } catch (err) {
    console.error('❌ Error conexión:', err)
    error.value = 'Error de conexión con el servidor'
  }
}
  /*
  fetch('http://localhost/bbdd.php?action=logearse&usuario='+nombreUsuario.value+'&email='+emailUsuario.value+'&contraseña='+contraUsuario.value)
  .then(response => {
    if (!response.ok) {
      throw new Error('Error HTTP: ' + response.status);
    }
    return response.json();
  })
  .then(data => console.log(data))
  .catch(error => {
    console.error('Error en la petición o en HTTP:', error);
 });
*/

</script>

<template>
      <form
        class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 -mt-8 flex flex-col gap-6 backdrop-blur"
        method="GET"
      >
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
