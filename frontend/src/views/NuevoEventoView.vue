<script setup>
import { ref } from 'vue'

const titulo = ref('')
const descripcion = ref('')
const fecha = ref('')
const hora = ref('')
const plazas = ref('')
const tipo = ref('')
const tipoPersonalizado = ref('')
const imagen = ref(null)

const onImagenChange = (e) => {
  imagen.value = e.target.files[0]
}

const crearEvento = () => {
  const tipoFinal =
    tipo.value === 'otro' ? tipoPersonalizado.value : tipo.value

  const evento = {
    titulo: titulo.value,
    descripcion: descripcion.value,
    fecha: fecha.value,
    hora: hora.value,
    plazas: Number(plazas.value),
    tipo: tipoFinal,
    imagen: imagen.value ? imagen.value.name : null
  }

  console.log('EVENTO CREADO:', evento)
}
</script>

<template>
  <section class="min-h-screen flex justify-center pt-28 px-4">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl p-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Crear evento
      </h1>

      <form @submit.prevent="crearEvento" class="flex flex-col gap-5">
        <!-- Título -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Título
          </label>
          <input
            v-model="titulo"
            type="text"
            placeholder="Nombre del evento"
            class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
          />
        </div>

        <!-- Descripción -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Descripción
          </label>
          <textarea
            v-model="descripcion"
            rows="4"
            placeholder="Describe el evento"
            class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500 resize-none"
          ></textarea>
        </div>

        <!-- Fecha y hora -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
              Fecha
            </label>
            <input
              v-model="fecha"
              type="date"
              class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
              Hora
            </label>
            <input
              v-model="hora"
              type="time"
              class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
            />
          </div>
        </div>

        <!-- Plazas -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Plazas disponibles
          </label>
          <input
            v-model="plazas"
            type="number"
            min="1"
            placeholder="Ej. 50"
            class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
          />
        </div>

        <!-- Tipo -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Tipo de evento
          </label>

          <select
            v-model="tipo"
            class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
          >
            <option value="">Selecciona un tipo</option>
            <option>Charla</option>
            <option>Taller</option>
            <option>Torneo</option>
            <option value="otro">Otro</option>
          </select>

          <input
            v-if="tipo === 'otro'"
            v-model="tipoPersonalizado"
            placeholder="Escribe el tipo"
            class="mt-2 w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-pink-500"
          />
        </div>

        <!-- Imagen -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Imagen del evento
          </label>
          <input
            type="file"
            accept="image/*"
            @change="onImagenChange"
            class="w-full text-sm"
          />
        </div>

        <!-- Botón -->
        <button
          type="submit"
          class="mt-4 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition"
        >
          Crear evento
        </button>
      </form>
    </div>
  </section>
</template>
