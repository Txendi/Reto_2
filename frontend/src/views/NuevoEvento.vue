<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router' // Importamos para poder redirigir

const router = useRouter() // Inicializamos el router

// Referencias para el formulario
const titulo = ref('')
const descripcion = ref('')
const fecha = ref('')
const hora = ref('')
const plazas = ref('')
const tipo = ref('')
const tipoPersonalizado = ref('')
const imagen = ref(null)

// Captura el archivo de imagen cuando el usuario lo selecciona
const onImagenChange = (e) => {
  imagen.value = e.target.files[0]
}

const crearEvento = async () => {
  // Usamos FormData para empaquetar archivos y texto
  const formData = new FormData()
  const tipoFinal = tipo.value === 'otro' ? tipoPersonalizado.value : tipo.value

  formData.append('titulo', titulo.value)
  formData.append('descripcion', descripcion.value)
  formData.append('fecha', fecha.value)
  formData.append('hora', hora.value)
  formData.append('plazas', plazas.value)
  formData.append('tipo', tipoFinal)

  if (imagen.value) {
    formData.append('imagen', imagen.value)
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}admin.php`, {
      method: 'POST',
      body: formData,
    })

    const data = await response.json()

    if (data.ok) {
      alert('¡Evento creado con éxito!')
      router.push('/events')
    } else {
      alert('Error del servidor: ' + (data.error || 'Desconocido'))
    }
  } catch (error) {
    alert('Fallo de red o servidor: ' + error.message)
  }
}
</script>

<template>
  <section class="min-h-screen flex justify-center pt-28 px-4 bg-black/70 text-white pb-10">
    <div
      class="w-full max-w-xl bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700 h-fit"
    >
      <h1 class="text-3xl font-bold mb-6 text-white text-center underline">Crear Nuevo Evento</h1>

      <form @submit.prevent="crearEvento" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-400 mb-1">Título del Evento</label>
          <input
            v-model="titulo"
            type="text"
            placeholder="Taller de Unreal Engine 5"
            required
            class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white transition-colors"
          />
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-400 mb-1">Descripción</label>
          <textarea
            v-model="descripcion"
            rows="3"
            placeholder="Introducción práctica: creación de escena, iluminación, Blueprints y primeros pasos hacia un prototipo jugable."
            class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white resize-none transition-colors"
          ></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-400 mb-1">Fecha</label>
            <input
              v-model="fecha"
              type="date"
              required
              class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-400 mb-1">Hora</label>
            <input
              v-model="hora"
              type="time"
              required
              class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-400 mb-1">Plazas Disponibles</label>
            <input
              v-model="plazas"
              type="number"
              min="1"
              placeholder="50"
              required
              class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-400 mb-1">Categoría</label>
            <select
              v-model="tipo"
              required
              class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-xl focus:border-blue-500 outline-none text-white cursor-pointer"
            >
              <option value="" disabled selected>Elige una opcion</option>
              <option value="charla">Charla</option>
              <option value="competicion">Competición</option>
              <option value="exhibicion">Exhibición</option>
              <option value="mesa redonda">Mesa Redonda</option>
              <option value="networking">Networking</option>
              <option value="presentacion">Presentación</option>
              <option value="taller">Taller</option>
              <option value="torneo">Torneo</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-400 mb-2"
            >Cartel del evento (Imagen)</label
          >
          <div class="flex items-center justify-center w-full">
            <label
              class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-700 border-dashed rounded-xl cursor-pointer bg-gray-900 hover:bg-gray-800 transition-colors"
            >
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <p class="mb-2 text-sm text-gray-400">
                  <span class="font-bold">Haz clic para subir o arrastra</span>
                </p>
                <p class="text-xs text-gray-500">{{ imagen ? imagen.name : 'PNG, JPG o GIF' }}</p>
              </div>
              <input type="file" accept="image/*" @change="onImagenChange" class="hidden" />
            </label>
          </div>
        </div>

        <button
          type="submit"
          class="w-full mt-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold py-3 rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-blue-500/20"
        >
          Publicar Evento
        </button>
      </form>
    </div>
  </section>
</template>

<style scoped>
/* Animación simple para que el campo 'otro' aparezca suavemente */
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>