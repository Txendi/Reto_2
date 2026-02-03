<script setup>
import { ref, onMounted, watch } from 'vue';

const eventos = ref([]);
const cargando = ref(true);
const error = ref('');
const tipos = ref([]);
const tipoSeleccionado = ref('');
const paginaActual = ref(0);
const totalPaginas = ref(0);
const soloConPlazas = ref(false);
const eventoActivo = ref(null);
const fechaSeleccionada = ref('');

const cargarEventos = async () => {
    try {
        cargando.value = true
        error.value = ''

        const params = new URLSearchParams({
            pagina: paginaActual.value
        })

        if (tipoSeleccionado.value) {
            params.append('tipo', tipoSeleccionado.value)
        }

        if (fechaSeleccionada.value) {
            params.append('fecha', fechaSeleccionada.value)
        }

        if (soloConPlazas.value) {
            params.append('plazas', 1)
        }

        const response = await fetch(`http://localhost/events/${params.toString()}`)

        if (!response.ok) {
            throw new Error('Error HTTP ' + response.status)
        }
        const data = await response.json()

        eventos.value = data.eventos
        totalPaginas.value = data.totalPaginas

    } catch (e) {
        error.value = 'No se han podido cargar los eventos'
    } finally {
        cargando.value = false
    }
}


const cargarTipos = async () => {

    const respuesta = await fetch(`http://localhost/events/tipos`)

    tipos.value=await respuesta.json();

}


watch(tipoSeleccionado, async () => {
    paginaActual.value = 0
    await cargarEventos()
})

watch(fechaSeleccionada, async () => {
    paginaActual.value = 0
    await cargarEventos()
})


watch(soloConPlazas, async () => {
    paginaActual.value = 0
    await cargarEventos()
})

watch(eventoActivo, (nuevoValor) => {
    if (nuevoValor) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
})



onMounted(() => {
    cargarEventos()
    cargarTipos()
})

const cambiarPagina = (numPagina) => {
    paginaActual.value = numPagina;
    cargarEventos();
}

</script>

<template>

    <section class="max-w-7xl mx-auto px-4 pt-8 pb-25">
        <h1 class="text-3xl text-white font-bold mb-5">Lista de Eventos</h1>

        <div class="flex flex-col sm:flex-row">
            <select v-model="tipoSeleccionado"
                class="text-black mb-5 p-2 border-2 rounded-xl w-4xs sm:w-2/7 md:w-2/6 lg:w-2/5">

                <option value="">Tipos</option>

                <option v-for="tipo in tipos" :key="tipo" :value="tipo">
                    {{ tipo }}
                </option>

            </select>

            <input type="date" class="text-black mb-5 p-2 border-2 rounded-xl sm:mx-3" v-model="fechaSeleccionada">

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" v-model="soloConPlazas" class="w-4 h-4">

                <span class="text-white">Solo con plazas disponibles</span>
            </label>

        </div>

        <p v-if="cargando" class="text-gray-500">Cargando...</p>
        <p v-else-if="error" class="text-red-600">{{ error }}</p>

        <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            <article v-for="evento in eventos" :key="evento.id" @click="eventoActivo = evento"
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
            <button class="bg-yellow-200 px-5 py-2 cursor-pointer hover:bg-white" v-for="n in totalPaginas" :key="n"
                @click="cambiarPagina(n - 1)">
                {{ n }}
            </button>
        </div>

        <!-- Modal de evento -->
        <Transition name="fade">
            <div v-if="eventoActivo" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"
                @click="eventoActivo = null"></div>
        </Transition>

        <Transition name="fade">
            <div v-if="eventoActivo" class="fixed inset-0 z-50 flex items-center justify-center"
                @click="eventoActivo = null">
                <div class="bg-white rounded-xl w-full max-w-lg p-6 relative">
                    <!-- <button class="absolute top-3 right-3 text-xl" @click="eventoActivo = null">❌</button>-->
                    <h2 class="text-2xl font-bold mb-4">
                        {{ eventoActivo.titulo }}
                    </h2>

                    <img :src="`/img/events/${eventoActivo.imagen}`" class="w-full h-56 object-cover rounded mb-4" />

                    <p class="mb-2"><strong>Tipo:</strong> {{ eventoActivo.tipo }}</p>
                    <p class="mb-2">
                        <strong>Fecha:</strong>
                        {{ eventoActivo.fecha }}
                    </p>
                    <p class="mb-2">
                        <strong>Hora:</strong>
                        {{ eventoActivo.hora }}
                    </p>
                    <p class="mb-2">
                        <strong>Plazas:</strong>
                        {{ eventoActivo.plazasLibres }}
                    </p>

                    <p class="text-gray-700">
                        <strong>Descripcion:</strong>
                        {{ eventoActivo.descripcion }}
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