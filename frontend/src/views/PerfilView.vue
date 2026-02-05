<script setup>
import { useUserStore } from '../stores/userStore.js'
import { onMounted, reactive } from 'vue'
const userStore = useUserStore()
const userRol = !userStore.isAdmin ? 'Usuario normal' : 'Administrador'

const data = reactive({ array: [] })

async function desapuntar(idEvento) {
  try {
    console.log("idEvento: "+ idEvento);
    const response = await fetch('http://localhost/user/desapuntar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      credentials: 'include',
      body: JSON.stringify({
        idEvento: idEvento
      })
    })
    if (!response.ok) {
      throw new Error('Error de HTTP: ' + response.status)
    }
    console.log(await response.text())
    let elem = data.array.find(elem => elem.id == idEvento);
    data.array.splice(data.array.indexOf(elem), 1);
  } catch (e) {
    console.log(e)
  }
}



async function pedirEventos() {
  try {
    /////////////////////////////////////////////////////////
    console.log('👤 Perfil → user id:', userStore.user.id)
    ///////////////////////////////////////////////////////////////
    const response = await fetch('http://localhost/user/events', {
      credentials: 'include'
    })

    if (!response.ok) {
      throw new Error('Error de HTTP: ' + response.status)
    }
    data.array = await response.json()
    console.log(data.array) // así puedes ver exactamente qué devuelve tu PHP
    console.log(123)
  } catch (e) {
    console.log(e)
  }
}
onMounted(
  // Cuando entre en la vista se va a poner a cargar los juegos
  pedirEventos,
)
</script>

<template>
  <div class="">
    <div class="bg-white shadow rounded-lg p-6 max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold mb-4 text-gray-800">Perfil de Usuario</h2>
      <div class="mb-6">
        <p class="text-lg text-gray-700">
          Bienvenido, <span class="font-semibold text-gray-800">{{ userStore.user.email }}</span>.
        </p>
        <p class="text-sm text-gray-500">
          Tipo de usuario: <span class="font-semibold text-blue-500">{{ userRol }}</span>
        </p>
      </div>
      <div>
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Mis Eventos</h3>
        <div class="grid grid-cols-1 gap-4" v-for="evento in data.array" :key="evento.id">
          <h1></h1>
          <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">
              {{ evento.titulo }}, {{ evento.id }}
            </h4>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-md text-gray-600">
                  <strong>Fecha:</strong> {{ evento.fecha }}
                </p>
                <p class="text-md text-gray-600">
                  <strong>Hora:</strong> {{ evento.hora }}
                </p>
                <button @click="desapuntar(evento.id)"
                  class="w-full mt-3 bg-purple-800 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300">
                  Desapuntarse
                </button>
              </div>
              <img :src="`/img/events/${evento.imagen}`" :alt=evento.titulo
                class="w-1/2 h-20 object-cover rounded-lg border border-gray-300" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
