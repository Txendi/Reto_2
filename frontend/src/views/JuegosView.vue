  <script setup>
import { ref, reactive, onMounted, watch } from 'vue'

//-> Es como que se ejecuta de nuevo (se monta encima del componente)
//-> Reacciona a los cambios de la variable

const api = 'http://localhost/bbdd.php?action=listaJuegos'

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
    const url = `${api}&q=${encodeURIComponent(busqueda.value)}` // el encode sirve para que no deje espacion ni huecos raros

    const response = await fetch(url)
    if (!response.ok) throw new Error('Error HTTP ' + response.status) // Si no encuentra la URL

    juegos.array = await response.json()
    console.log(juegos.array)
    console.log(busqueda.value)
  } catch (e) {
    error.value = 'No se han podido cargar los juegos'
  } finally {
    cargando.value = false
  }
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
  <section class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-5">Lista de Videojuegos</h1>

    <input
      type="text"
      placeholder="Buscar juegos..."
      class="mb-5 p-2 border-2 rounded-xl w-full"
      v-model="busqueda"
    />

    <p v-if="cargando" class="text-gray-500">Cargando...</p>
    <p v-else-if="error" class="text-red-600">{{ error }}</p>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      <article
        v-for="juego in juegos.array"
        :key="juego.id"
        @click="juegoActivo = juego"
        class="bg-gray-200 border-b-gray-800 rounded-lg shadow-gray-700 hover:bg-gray-300 hover:shadow-xl cursor-pointer flex flex-col transition-transform duration-300 ease-in-out hover:scale-110"
      >
        <img
          :src="`/img/games/${juego.imagen}`"
          :alt="juego.titulo"
          class="w-full h-56 object-cover rounded-lg"
        />

        <div class="p-4 flex flex-col gap-2 flex-gro">
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

    <Transition name="fade">
      <div
        v-if="juegoActivo"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
        @click="juegoActivo = null"
      ></div>
    </Transition>

    <Transition name="fade">
      <div v-if="juegoActivo" class="fixed inset-0 z-50 flex items-center justify-center"
      @click="juegoActivo=null">
        <div class="bg-white rounded-xl w-full max-w-lg p-6 relative">
<!--           <button class="absolute top-3 right-3 text-xl" @click="juegoActivo = null">❌</button>
 -->
          <h2 class="text-2xl font-bold mb-4">
            {{ juegoActivo.titulo }}
          </h2>

          <img
            :src="`/img/games/${juegoActivo.imagen}`"
            class="w-full h-56 object-cover rounded mb-4"
          />

          <p class="mb-2"><strong>Género:</strong> {{ juegoActivo.genero }}</p>
          <p class="mb-2">
            <strong>Plataformas:</strong>
            {{
              Array.isArray(juegoActivo.plataformas)
                ? juegoActivo.plataformas.join(', ')
                : juegoActivo.plataformas
            }}
          </p>

          <p class="text-gray-700">
            {{ juegoActivo.descripcion }}
          </p>
        </div>
      </div>
    </Transition>
  </section>
</template>

<!-- Animacion creada con IA -->

  <style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.25s ease;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(1.2);
}
</style>