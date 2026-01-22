<script setup>
import headerHecho from '../src/components/header.vue'
import footerHecho from '../src/components/Footer.vue'

import { RouterLink, RouterView } from 'vue-router'
//import HelloWorld from './components/HelloWorld.vue'
function prueba(){
  fetch('http://localhost/bbdd.php')
    .then(response => response.json()) // parsea JSON
    .then(data => {
      console.log('Datos JSON:', data); // data es un objeto JS
    })
    .catch(error => {
      console.error('Error en la petición:', error);
    });
  }
</script>

<template>
<<<<<<< HEAD
<div>
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <HelloWorld class="text-red-200" msg="You did it!" />

      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
      </nav>
    </div>
  </header>
  <button @click="prueba">
    Hola
  </button>

=======
<div class="contenedorPadre">
  <headerHecho />
 <footerHecho />
>>>>>>> develop
  <RouterView/>
</div>
</template>

<style scoped>

</style>
