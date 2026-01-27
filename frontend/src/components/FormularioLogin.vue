
<script setup></script>

<template>
        <form class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 -mt-8 flex flex-col gap-6">
        <div>
          <label for="usuario" class="block text-gray-700 font-semibold mb-2">Usuario</label>
          <input
            type="text"
            id="usuario"
            class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
            placeholder="Introduzca nombre de usuario..."
          />
        </div>
        <div>
          <label for="contraseña" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
          <input
            type="password"
            id="contraseña"
            class="bg-gray-100 rounded-xl w-full p-3 focus:outline-none focus:ring-2 focus:ring-pink-400 transition"
            placeholder="Introduzca su contraseña..."
          />
        </div>
        <button
          type="submit"
          class="mt-4 bg-[rgba(222,26,88,1)] hover:bg-pink-700 text-[rgba(244,179,66,1)] font-bold py-3 rounded-xl shadow transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 hover:shadow-pink-300/60 active:scale-95"
        >
          Entrar
        </button>
      </form>
</template>

<style scoped>
.router-link-exact-active {
  background: #fff !important;
  border-bottom-width: 4px !important;
  border-color: rgba(222, 26, 88, 1) !important;
  color: rgba(222, 26, 88, 1) !important;
  z-index: 10;
}
</style>
