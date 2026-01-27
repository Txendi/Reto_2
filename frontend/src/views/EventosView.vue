<script setup>
import { ref, computed, onMounted } from 'vue';

const eventos = ref([]);
const cargando = ref(true);
const error = ref('');
const tipoSeleccionado = ref('');
const paginaActual = ref(0);
const totalPaginas = ref(0)



const cargarEventos = async () => {
    try {
        const response = await fetch(`http://localhost/bbdd.php?action=listaEventos&pagina=${paginaActual.value}`)
        if (!response.ok) {
            throw new Error('Error de HTTP: ' + response.status)
        }
        const data = await response.json()
        console.log(data)  // así puedes ver exactamente qué devuelve tu PHP
        totalPaginas.value = data[0]
        eventos.value = data[1]


    } catch (e) {
        error.value = 'No se ha podido cargar la pagina con los eventos'
    } finally {
        cargando.value = false
    }
}


onMounted(() => {
    cargarEventos()
})

const cambiarPagina = (numPagina) => {
    paginaActual.value = numPagina;
    cargarEventos();
}

</script>

<template>

    <section class="max-w-7xl mx-auto px-4 pt-8 pb-25">
        <h1 class="text-3xl text-white font-bold mb-5">Lista de Eventos</h1>


        <select v-model="tipoSeleccionado" class="text-black mb-5 p-2 border-2 rounded-xl w-3xl">

            <option value="">Tipos</option>

            <option v-for="evento in eventos" :key="evento.tipo" :value="evento.tipo">
                {{ evento.tipo }}
            </option>

        </select>



        <p v-if="cargando" class="text-gray-500">Cargando...</p>
        <p v-else-if="error" class="text-red-600">{{ error }}</p>

        <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            <article v-for="evento in eventos" :key="evento.id"
                class="bg-gray-200 border-b-gray-800 rounded-lg shadow hover:shadow-xl cursor-pointer flex flex-col">
                <img :src="`/img/events/${evento.imagen}`" :alt="evento.titulo"
                    class="w-full h-56 object-cover rounded-lg" />

                <div class="p-4 flex flex-col gap-2 flex-grow">
                    <h3 class="text-lg font-semibold text-center">
                        {{ evento.titulo }}
                    </h3>
                    <div class="flex items-end gap-3">
                        <div class="h-full w-1/2 ">
                            <p class="text-sm text-gray-600 text-center">
                                {{ evento.tipo }}
                            </p>
                        </div>
                        <div class="h-full w-full justify-end">
                            <p class="text-sm text-gray-600 text-center">
                                <span class="font-bold">Plazas: </span>
                                {{ evento.plazasLibres }}
                            </p>
                        </div>

                    </div>
                    <p class="text-sm text-gray-600">
                        <span class="font-bold">Descripcion: </span>
                        {{ evento.descripcion }}
                    </p>

                    <div class="h-full flex justify-center items-end gap-3">
                        <p class="text-sm text-gray-700 ">
                            {{ evento.hora }}
                        </p>
                        <p class="text-sm text-gray-700 ">
                            {{ evento.fecha }}
                        </p>

                    </div>
                </div>
            </article>
        </div>
        <div class="flex justify-center mt-7 gap-8">
            <button class="bg-yellow-200 px-5 py-2 cursor-pointer hover:bg-white" v-for="n in totalPaginas" :key="n" @click="cambiarPagina(n - 1)">
                {{ n }}
            </button>
        </div>
    </section>
</template>

<style></style>