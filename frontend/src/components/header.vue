<script setup>
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/userStore'
import { ref } from 'vue'

const router = useRouter()
const userStore = useUserStore()
const menuAbierto = ref(false)

const isOpen = ref(false);

const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};


const logout = async () => {
  try {
    await fetch('http://localhost/auth/logout', {
      method: 'POST',
      credentials: 'include'
    })

    userStore.logout()
    isOpen.value = false
    menuAbierto.value = false

    router.push('/login')

    console.log('Sesión cerrada correctamente')
  } catch (e) {
    console.error('Error al cerrar sesión', e)
  }
}


</script>

<template>
  <header>
    <nav
      class="contenedorPrincipal h-20 flex items-center justify-between px-8 shadow-lg relative"
    >
      <div class="flex items-center space-x-4 ml-12">
        <img src="../img/logo.png" class="size-30 h-auto" alt="logo Elofest" />
      </div>

      <div class="hidden md:flex items-center space-x-8">
        <router-link
          to="/juegos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
          >Juegos
        </router-link>
        <router-link
          to="/eventos"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
          >Eventos
        </router-link>
        <router-link
          v-if="userStore.isAdmin"
          to="/nuevoEvento"
          class="textoHeader text-white font-semibold text-lg relative transition-colors duration-300"
          >Nuevo evento
        </router-link>
        <router-link
          v-if="!userStore.isAuthenticated"
          to="/login"
          class="botonRegistrarse ml-4 px-6 py-2 rounded-full font-bold text-[rgba(222,26,88,1)] bg-[rgba(244,179,66,1)] border-2 border-[rgba(222,26,88,1)] shadow-lg transition-all duration-500 focus:outline-none focus:shadow-[0_0_0_3px_rgba(222,26,88,0.33)]"
        >
          Registrarse / Iniciar sesión
        </router-link>
        <div class="menu-container">
          <router-link
            v-if="userStore.isAuthenticated"
            to="/perfil"
            @click="toggleMenu"
            class="botonRegistrarse ml-4 px-6 py-2 rounded-full font-bold text-[rgba(222,26,88,1)] bg-[rgba(244,179,66,1)] border-2 border-[rgba(222,26,88,1)] shadow-lg transition-all duration-500 focus:outline-none focus:shadow-[0_0_0_3px_rgba(222,26,88,0.33)]"
          >
            {{ userStore.user.username }}
          </router-link>
          <ul v-if="isOpen" class="dropdown bg-[#DE1A58] text-white font-bold rounded absolute top-20 right-4 border">
            <li @click="logout" class="logout-item p-4 hover:bg-[#F4B342] hover:scale-105">Cerrar Sesión</li>
          </ul>
        </div>
      </div>

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

      <transition name="fade">
        <div
          v-if="menuAbierto"
          class="absolute top-20 right-4 z-50 bg-white rounded-2xl shadow-2xl flex flex-col w-64 py-6 px-4 space-y-2 border border-pink-100 animate-fade-in"
        >
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
          <router-link
            v-if="userStore.isAdmin"
            to="/eventos"
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
          <button
            v-if="userStore.isAuthenticated"
            @click.prevent="logout(); menuAbierto = false"
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
.contenedorPrincipal {
  background: linear-gradient(
    90deg,
    rgba(244, 179, 66, 1) 0%,
    rgba(222, 26, 88, 1) 40%,
    rgba(54, 1, 133, 1) 100%
  );
}

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

.botonRegistrarse:hover {
  background-color: #de1a58 !important;
  color: #f4b342 !important;
  border-color: #f4b342 !important;
  transform: scale(1.15);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
}
</style>
