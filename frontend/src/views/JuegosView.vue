<script setup>
</script>


<template>
  <div class="min-h-screen bg-gray-100">
    <h1 class="text-2xl font-semibold bg-purple-500 text-white py-4 text-center">Juegos</h1>
    <input type="text" placeholder="Escribe aqui el juego que busques..." />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-8 max-w-7xl mx-auto">
      <div
        v-for="(juego, index) in juegos"
        :key="index"
        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
      >
        <div
          class="h-48 bg-gradient-to-br from-purple-300 to-purple-500 flex items-center justify-center text-white text-2xl font-bold"
        >
          {{ juego.nombre }}
        </div>

        <div class="p-5 text-center">
          <h2 class="text-lg font-bold text-gray-800 mb-1">
            {{ juego.nombre }}
          </h2>
          <p class="text-sm text-gray-600 mb-4">Género: {{ juego.genero }}</p>
          <button
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors"
          >
            {{ juego.boton }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

