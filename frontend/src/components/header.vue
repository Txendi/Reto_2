<script setup>
/** * IMPORTACIONES
 */
import { useRoute } from 'vue-router'
import { useUserStore } from '../stores/userStore'
import { ref, watch, onMounted, onUnmounted } from 'vue'

/** * ESTADOS Y VARIABLES
 */
const route = useRoute()
const userStore = useUserStore()

// Controla la visibilidad del menú lateral en móviles
const menuAbierto = ref(false)

// Controla el desplegable de "Cerrar Sesión" en la versión de escritorio
const isOpen = ref(false)

/** * LÓGICA DE NAVEGACIÓN Y RESIZE
 */

// BUG FIX: Observador para cerrar menús al navegar.
// Si el usuario cambia de página (ej. va a /juegos), los menús se cierran.
// Se mantiene abierto 'isOpen' solo si la nueva ruta es '/perfil'.
watch(
  () => route.path,
  (newPath) => {
    if (newPath !== '/perfil') {
      isOpen.value = false
    }
    menuAbierto.value = false
  }
)

// Función para resetear estados cuando se redimensiona la ventana.
// Si pasas de móvil a escritorio o viceversa, los menús se limpian para evitar solapamientos visuales.
const handleResize = () => {
  if (window.innerWidth >= 768) {
    // Si entramos en modo escritorio, cerramos el menú hamburguesa
    menuAbierto.value = false
  } else {
    // Si entramos en modo móvil, cerramos el desplegable de perfil
    isOpen.value = false
  }
}

// Registramos el evento de redimensión al montar el componente
onMounted(() => {
  window.addEventListener('resize', handleResize)
})

// Limpiamos el evento al destruir el componente para evitar fugas de memoria
onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

/** * FUNCIONES DE ACCIÓN
 */

// Alterna el estado del desplegable de perfil y cierra el móvil si está abierto
const toggleMenu = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value) menuAbierto.value = false
}

// Cierra la sesión en el servidor y limpia el estado local
const logout = async () => {
  try {
    await fetch('http://localhost/auth/logout', {
      method: 'POST',
      credentials: 'include',
    })

    userStore.logout() // Limpia los datos del usuario en la store
    isOpen.value = false
    menuAbierto.value = false

    console.log('Sesión cerrada correctamente')
  } catch (e) {
    console.error('Error al cerrar sesión', e)
  }
}
</script>

<template>
  <!-- Header con barra de navegación -->
  <header>
    <nav class="contenedorPrincipal h-20 flex items-center justify-between px-8 shadow-lg relative">
      <!-- Logo a la izquierda, en móviles se ajusta para ocupar menos espacio -->
      <div class="flex items-center space-x-4 ml-12">
        <img src="../img/logo.png" class="size-30 h-auto" alt="logo Elofest" />
      </div>

      <!-- Menú de navegación visible solo en pantallas grandes (md+) -->
      <div class="hidden md:flex items-center space-x-8">
        <!-- Enlace a la sección "Juegos" -->
        <router-link
          to="/juegos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
        >
          Juegos
        </router-link>

        <!-- Enlace a la sección "Eventos" -->
        <router-link
          to="/eventos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
        >
          Eventos
        </router-link>

        <!-- Enlace para crear un nuevo evento, visible solo si el usuario es admin -->
        <router-link
          v-if="userStore.isAdmin"
          to="/nuevoEvento"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
        >
          Nuevo evento
        </router-link>

        <!-- Enlace para registrarse/iniciar sesión, visible solo si el usuario no está autenticado -->
        <router-link
          v-if="!userStore.isAuthenticated"
          to="/login"
          class="botonRegistrarse ml-4 px-6 py-2 rounded-full font-bold text-[rgba(222,26,88,1)] bg-[rgba(244,179,66,1)] border-2 border-[rgba(222,26,88,1)] shadow-lg transition-all duration-500"
        >
          Registrarse / Iniciar sesión
        </router-link>

        <!-- Menú desplegable de usuario autenticado, visible solo si el usuario está autenticado -->
        <div class="menu-container">
          <router-link
            v-if="userStore.isAuthenticated"
            to="/perfil"
            @click="toggleMenu"
            class="botonRegistrarse ml-4 px-6 py-2 rounded-full font-bold text-[rgba(222,26,88,1)] bg-[rgba(244,179,66,1)] border-2 border-[rgba(222,26,88,1)] shadow-lg transition-all duration-500"
          >
            {{ userStore.user.username }}
          </router-link>

          <!-- Menú desplegable con opción de cerrar sesión -->
          <router-link
            v-if="isOpen"
            class="dropdown bg-[#DE1A58] text-white font-bold rounded absolute top-20 right-4 border" @click="logout"
            to="/juegos"
          >
          </router-link>
          <ul
            v-if="isOpen"
            class="dropdown bg-[#DE1A58] text-white font-bold rounded absolute top-20 right-4 border"
          >
            <li
              @click="logout"
              class="logout-item p-4 hover:bg-[#F4B342] hover:scale-105 cursor-pointer"
            >
              Cerrar Sesión
            </li>
          </ul>
        </div>
      </div>

      <!-- Menú hamburguesa en dispositivos móviles -->
      <button
        class="md:hidden flex items-center text-white focus:outline-none"
        @click="menuAbierto = !menuAbierto"
        aria-label="Abrir menú"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="w-8 h-8"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
          />
        </svg>
      </button>

      <!-- Menú desplegable en móviles con transiciones -->
      <transition name="fade">
        <div
          v-if="menuAbierto"
          class="md:hidden absolute top-20 right-4 z-50 bg-white rounded-2xl shadow-2xl flex flex-col w-64 py-6 px-4 space-y-2 border border-pink-100 animate-fade-in"
        >
          <!-- Enlace a "Juegos" en el menú desplegable móvil -->
          <router-link
            to="/juegos"
            class="flex items-center gap-3 w-full text-[rgba(222,26,88,1)] font-semibold text-lg py-3 px-4 rounded-xl hover:bg-pink-200 transition"
            @click="menuAbierto = false"
          >
            <svg
              class="w-6 h-6 text-[rgba(222,26,88,1)]"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <circle cx="12" cy="12" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12a6 6 0 0112 0" />
            </svg>
            Juegos
          </router-link>

          <!-- Enlace a "Eventos" en el menú desplegable móvil -->
          <router-link
            to="/eventos"
            class="flex items-center gap-3 w-full text-[rgba(222,26,88,1)] font-semibold text-lg py-3 px-4 rounded-xl hover:bg-pink-200 transition"
            @click="menuAbierto = false"
          >
            <svg
              class="w-6 h-6 text-[rgba(222,26,88,1)]"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-4-4v8" />
            </svg>
            Eventos
          </router-link>

          <!-- Enlace para crear un nuevo evento, solo visible para admin -->
          <router-link
            v-if="userStore.isAdmin"
            to="/nuevoEvento"
            class="flex items-center gap-3 w-full text-[rgba(222,26,88,1)] font-semibold text-lg py-3 px-4 rounded-xl hover:bg-pink-50 transition"
            @click="menuAbierto = false"
          >
            <svg
              class="w-6 h-6 text-[rgba(222,26,88,1)]"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-4-4v8" />
            </svg>
            Nuevo evento
          </router-link>

          <!-- Enlace para registrarse/iniciar sesión si no está autenticado -->
          <router-link
            v-if="!userStore.isAuthenticated"
            to="/login"
            class="flex items-center gap-3 w-full font-bold py-3 px-4 rounded-xl text-white bg-[rgba(222,26,88,1)] hover:bg-pink-700 shadow transition"
            @click="menuAbierto = false"
          >
            <svg
              class="w-6 h-6 text-white"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25V9m7.5 0v10.5A2.25 2.25 0 0113.5 21h-3a2.25 2.25 0 01-2.25-2.25V9m7.5 0H6.75"
              />
            </svg>
            Registrarse / Iniciar sesión
          </router-link>

          <!-- Enlace al perfil, visible solo si el usuario está autenticado -->
          <router-link
            v-if="userStore.isAuthenticated"
            to="/perfil"
            class="flex items-center gap-3 w-full font-bold py-3 px-4 rounded-xl text-white bg-[rgba(222,26,88,1)] hover:bg-pink-700 shadow transition"
            @click="menuAbierto = false"
          >
            <svg
              class="w-6 h-6 text-white"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25V9m7.5 0v10.5A2.25 2.25 0 0113.5 21h-3a2.25 2.25 0 01-2.25-2.25V9m7.5 0H6.75"
              />
            </svg>
            {{ userStore.user.username }}
          </router-link>

          <!-- Botón para cerrar sesión, visible solo si el usuario está autenticado -->
          <button
            v-if="userStore.isAuthenticated"
            @click.prevent="
              logout(),
              menuAbierto = false
            "
            class="flex items-center gap-3 w-full text-[rgba(222,26,88,1)] font-semibold text-lg py-3 px-4 rounded-xl hover:bg-pink-50 transition"
          >
            <svg
              class="w-6 h-6 text-[rgba(222,26,88,1)]"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <circle cx="12" cy="12" r="4" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12a6 6 0 0112 0" />
            </svg>
            Cerrar sesión
          </button>
        </div>
      </transition>
    </nav>
  </header>
</template>

<style scoped>
/* Estilos del degradado del Header */
.contenedorPrincipal {
  background: linear-gradient(
    90deg,
    rgba(244, 179, 66, 1) 0%,
    rgba(222, 26, 88, 1) 40%,
    rgba(54, 1, 133, 1) 100%
  );
}

/* Efecto hover subrayado animado */
.textoHeader::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -1px;
  width: 0;
  height: 3px;
  background: linear-gradient(90deg, #f4b342 0%, #de1a58 100%);
  border-radius: 2px;
  transition: width 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.textoHeader:hover::after {
  width: 100%;
}

/* Botón de registro con animación de escala */
.botonRegistrarse:hover {
  background-color: #de1a58 !important;
  color: #f4b342 !important;
  border-color: #f4b342 !important;
  transform: scale(1.15);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
}
</style>