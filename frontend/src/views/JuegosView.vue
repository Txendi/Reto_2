    <script setup>
import { ref, reactive, onMounted, watch } from 'vue'

//-> La lista de los juegos
const juegos = reactive({ array: [] })

const cargando = ref(false) //-> Para el mensaje de cuando cargue los juegos,
const error = ref('') // |_> A su vez mostrara el mensaje de error si no los encuentra

//-> Para el input
const busqueda = ref('')

const juegoActivo = ref(null)

const fetchJuegos = async () => {
  cargando.value = true
  error.value = ''

  try {
    const url = `http://localhost/games/${encodeURIComponent(busqueda.value)}`

    const response = await fetch(url)
    if (!response.ok) throw new Error('Error HTTP ' + response.status)

    const data = await response.json()

    juegos.array = Array.isArray(data) ? data : [data]
  } catch (e) {
    error.value = 'No se han podido cargar los juegos'
  } finally {
    cargando.value = false
  }
}

const cargarDetalle = async (id) => {
  try {
    const response = await fetch(`http://localhost/game/${id}`)
    if (!response.ok) throw new Error('Error al cargar detalle')

    const data = await response.json()

    juegoActivo.value = Array.isArray(data) ? data[0] : data
    console.log(data)
    console.log(juegoActivo.value.id)
  } catch (e) {
    error.value = 'No se pudo cargar la información del juego'
  }
}

const limpiarPlataformas = (p) => {
  if (!p) return ''
  if (Array.isArray(p)) return p.join(', ')
  return String(p)
    .replace(/[[\]"]/g, '')
    .replace(/\\/g, '')
}

onMounted(() => {
  // Cuando entre en la vista se va a poner a cargar los juegos
  fetchJuegos()
})

watch(busqueda, () => {
  // Como el propio nombre dice, mira para ver que cada vez que la variable busqueda (el input)
  fetchJuegos() // va cambiando mientras escribe, y se actualiza a la vez
})

watch(juegoActivo, (nuevoValor) => {
  if (nuevoValor) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
})
</script>

<template>
  <div class="min-h-screen bg-black/70 py-10 px-4 text-white">
    <div class="max-w-6xl mx-auto">
      <header class="mb-10 text-center">
        <h1 class="text-4xl font-bold mb-4">Catálogo de Juegos</h1>
        <input
          v-model="busqueda"
          type="text"
          placeholder="Buscar juego..."
          class="w-full max-w-xl p-3 rounded-lg bg-gray-800 border border-gray-600 text-white focus:outline-none focus:border-blue-500"
        />
      </header>

      <div v-if="!cargando" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <article
          v-for="juego in juegos.array"
          :key="juego.id"
          @click="cargarDetalle(juego.id)"
          class="group bg-gray-800 border-2 border-transparent hover:border-blue-800 hover:scale-110 rounded-xl overflow-hidden cursor-pointer transition-all duration-300 shadow-lg max-w-sm mx-auto w-full flex flex-col"
        >
          <img :src="`/img/games/${juego.imagen}`" class="w-full h-56 object-cover" />

          <div class="p-5 flex flex-col flex-grow">
            <h3 class="text-xl font-bold mb-1 group-hover:text-blue-400 transition-colors">
              {{ juego.titulo }}
            </h3>
            <p class="text-gray-300 text-lg font-semibold mb-3">{{ juego.genero }}</p>

            <p class="text-gray-500 text-sm mt-auto">
              <span class="opacity-60">Plataformas:</span><br />
              <span class="text-gray-300">{{ limpiarPlataformas(juego.plataformas) }}</span>
            </p>
          </div>
        </article>
      </div>

      <div v-else class="text-center py-20 text-xl animate-pulse">Cargando juegos...</div>
      <div v-if="error" class="text-red-500 text-center font-bold">{{ error }}</div>
    </div>

    <Transition name="fade">
      <div
        v-if="juegoActivo"
        class="fixed inset-0 bg-black/95 flex items-center justify-center p-4 z-50"
        @click.self="juegoActivo = null"
      >
        <div
          class="bg-gray-800 rounded-2xl max-w-2xl w-full relative shadow-2xl border border-gray-700 overflow-hidden"
        >
          <button
            @click="juegoActivo = null"
            class="absolute top-4 right-4 text-2xl bg-black/50 w-10 h-10 rounded-full hover:bg-red-600 transition-colors z-10"
          >
            ✕
          </button>

          <img :src="`/img/games/${juegoActivo.imagen}`" class="w-full h-64 sm:h-80 object-cover" />

          <div class="p-6">
            <h2 class="text-3xl font-bold mb-2">{{ juegoActivo.titulo }}</h2>
            <div class="flex gap-2 mb-4">
              <span class="bg-blue-600 px-2 py-1 rounded text-xs font-bold uppercase">{{
                juegoActivo.genero
              }}</span>
            </div>
            <p class="text-gray-400 text-sm mb-4">
              <strong>Disponible en:</strong> {{ limpiarPlataformas(juegoActivo.plataformas) }}
            </p>
            <p class="text-gray-300 leading-relaxed border-t border-gray-700 pt-4">
              {{ juegoActivo.descripcion }}
            </p>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
/* Animaciones y utilidades extra */
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

/* Tus transiciones originales */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Personalización de la barra de scroll para el modal */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #1f2937;
}

::-webkit-scrollbar-thumb {
  background: #4b5563;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style>