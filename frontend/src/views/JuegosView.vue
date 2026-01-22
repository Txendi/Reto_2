<script setup>
import { ref, onMounted } from 'vue'

const juegos = ref([])
const cargando = ref(true)
const error = ref('')

const api = 'http://localhost/bbdd.php?action=listaJuegos'

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
    <h1 class="text-3xl font-bold mb-8">Juegos</h1>

    <p v-if="cargando" class="text-gray-500">Cargando...</p>
    <p v-else-if="error" class="text-red-600">{{ error }}</p>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      <article
        v-for="juego in juegos"
        :key="juego.id"
        class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col"
      >
        <img
          :src="`/img/games/${juego.imagen}`"
          :alt="juego.titulo"
          class="w-full h-56 object-cover"
        />

        <div class="p-4 flex flex-col gap-2 flex-grow">
          <h3 class="text-lg font-semibold">
            {{ juego.titulo }}
          </h3>

          <p class="text-sm text-gray-600">
            {{ juego.genero }} ·
            {{
              Array.isArray(juego.plataformas) ? juego.plataformas.join(', ') : juego.plataformas
            }}
          </p>

          <p class="text-sm text-gray-700 flex-grow">
            {{ juego.descripcion }}
          </p>

          <button class="mt-3 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Ver más
          </button>
        </div>
      </article>
    </div>
  </section>
</template>



<style scoped>
</style>