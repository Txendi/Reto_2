<script setup>
import { ref, onMounted, computed } from 'vue'

const api = 'http://localhost/bbdd.php'

const juegos = ref([])
const cargando = ref(true)
const error = ref('')
const busqueda = ref('')

const juegosFiltrados = computed(() => {
  if (!busqueda.value) {
    return juegos.value
  }
  
  const texto = busqueda.value.toLowerCase()

  return juegos.value.filter((juego) => {
    const titulo = juego.titulo?.toLowerCase() || ''
    const genero = juego.genero?.toLowerCase() || ''
    const plataformas = Array.isArray(juego.plataformas)
      ? juego.plataformas.join(' ').toLowerCase()
      : (juego.plataformas || '').toLowerCase()

    return (
      titulo.includes(texto) ||
      genero.includes(texto) ||
      plataformas.includes(texto)
    )
  })
})

onMounted(async () => {
  try {
    const response = await fetch(api)
    if (!response.ok) {
      throw new Error('Error de HTTP: ' + response.status)
    }
    const data = await response.json()
    juegos.value = data
  } catch (e) {
    error.value = 'No se ha podido cargar la pagina con los juegos'
  } finally {
    cargando.value = false
  }
})
</script>

<template>
  <section class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-5">Lista de Videojuegos</h1>

    <input type="text" placeholder="Buscar juegos..." class="mb-5 p-2 border-2 rounded-xl w-full" v-model="busqueda"/>

    <p v-if="cargando" class="text-gray-500">Cargando...</p>
    <p v-else-if="error" class="text-red-600">{{ error }}</p>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      <article
        v-for="juego in juegosFiltrados"
        :key="juego.id"
        class="bg-gray-200 border-b-gray-800 rounded-lg shadow hover:shadow-xl cursor-pointer flex flex-col"
      >
        <img
          :src="`/img/games/${juego.imagen}`"
          :alt="juego.titulo"
          class="w-full h-56 object-cover rounded-lg"
        />

        <div class="p-4 flex flex-col gap-2 flex-grow">
          <h3 class="text-lg font-semibold">
            {{ juego.titulo }}
          </h3>

          <p class="text-sm text-gray-600">
            {{ juego.genero }}
          </p>

          <p class="text-sm text-gray-600">
            <span class="font-bold">Plataformas: </span>
            {{
              Array.isArray(juego.plataformas) ? juego.plataformas.join(', ') : juego.plataformas
            }}
          </p>

          <p class="text-sm text-gray-700 flex-grow">
            {{ juego.descripcion }}
          </p>
        </div>
      </article>
    </div>
  </section>
</template>

<style scoped>
</style>