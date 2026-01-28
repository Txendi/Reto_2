<script setup>
import { ref, onMounted, watch, reactive } from 'vue'

//-> Es como que se ejecuta de nuevo (se monta encima del componente)
//-> Reacciona a los cambios de la variable

const api = 'http://localhost/bbdd.php?action=listaJuegos'

//-> La lista de los juegos
const juegos = reactive({array: []})

const cargando = ref(false) //-> Para el mensaje de cuando cargue los juegos,
const error = ref('')      // |_> A su vez mostrara el mensaje de error si no los encuentra

//-> Para el input
const busqueda = ref('')

const fetchJuegos = async () => {
  cargando.value = true;
  error.value = '';

  try {

    const url = `${api}&q=${encodeURIComponent(busqueda.value)}`;  // el encode sirve para que no deje espacion ni huecos raros

    const response = await fetch(url);

    if (!response.ok) throw new Error('Error HTTP ' + response.status); // Si no encuentra la URL

    juegos.array = await response.json();


  } catch (e) {
    console.log(e);
  } finally {
    cargando.value = false;
  }
}

onMounted(() => {       // Cuando entre en la vista se va a poner a cargar los juegos
  fetchJuegos()
  
})

watch(busqueda, () => {  // Como el propio nombre dice, mira para ver que cada vez que la variable busqueda (el input)
  fetchJuegos()          // va cambiando mientras escribe, y se actualiza a la vez 
})

</script>

<template>
  <section class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-5">Lista de Videojuegos</h1>

    <input type="text" placeholder="Buscar juegos..." class="mb-5 p-2 border-2 rounded-xl w-full" v-model="busqueda" />

    <p v-if="cargando" class="text-gray-500">Cargando...</p>
    <p v-else-if="error" class="text-red-600">{{ error }}</p>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
      <article v-for="juego in juegos.array" :key="juego.id"
        class="bg-gray-200 border-b-gray-800 rounded-lg shadow hover:shadow-xl cursor-pointer flex flex-col">
        <img :src="`/img/games/${juego.imagen}`" :alt="juego.titulo" class="w-full h-56 object-cover rounded-lg" />

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

<style scoped></style>