<script setup>
import { ref, computed, onMounted } from 'vue';

const eventos = ref([]);
const cargando = ref(true);
const error = ref('');
const busqueda = ref('');
const paginaActual = ref(0);





const eventosFiltrados = computed(() => {
    if (!busqueda.value) {
        return eventos.value
    }

    const texto = busqueda.value.toLowerCase()

    return eventos.value.filter((evento) => {
        const titulo = evento.titulo?.toLowerCase() || ''
        const tipo = evento.tipo?.toLowerCase() || ''


        return (
            titulo.includes(texto) ||
            tipo.includes(texto)

        )
    })
})

const cargarEventos = async () => {
    try {
        const response = await fetch(`http://localhost/proyecto_elorrieta/bbdd.php?action=listaEventos&pagina=${paginaActual.value}`)
        if (!response.ok) {
            throw new Error('Error de HTTP: ' + response.status)
        }
        const data = await response.json()
        console.log(data)  // así puedes ver exactamente qué devuelve tu PHP
        eventos.value = data


    } catch (e) {
        error.value = 'No se ha podido cargar la pagina con los juegos'
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

        <input type="text" placeholder="Buscar eventos..." class="text-white mb-5 p-2 border-2 rounded-xl w-full"
            v-model="busqueda" />

        <p v-if="cargando" class="text-gray-500">Cargando...</p>
        <p v-else-if="error" class="text-red-600">{{ error }}</p>

        <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            <article v-for="evento in eventosFiltrados" :key="evento.id"
                class="bg-gray-200 border-b-gray-800 rounded-lg shadow hover:shadow-xl cursor-pointer flex flex-col">
                <img :src="`/img/games/${evento.imagen}`" :alt="evento.titulo"
                    class="w-full h-56 object-cover rounded-lg" />

                <div class="p-4 flex flex-col gap-2 flex-grow">
                    <h3 class="text-lg font-semibold text-center">
                        {{ evento.titulo }}
                    </h3>

                    <p class="text-sm text-gray-600 text-center">
                        {{ evento.tipo }}
                    </p>

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
            <button @click="cambiarPagina(0)" class=" bg-yellow-200 px-5 py-2 cursor-pointer">1</button>
            <button @click="cambiarPagina(1)" class=" bg-yellow-200 px-5 py-2 cursor-pointer">2</button>
            <button @click="cambiarPagina(2)" class=" bg-yellow-200 px-5 py-2 cursor-pointer">3</button>
        </div>
    </section>
</template>

<style></style>