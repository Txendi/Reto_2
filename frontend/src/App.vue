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
<div class="contenedorPadre">
  <headerHecho />
  
 <footerHecho />
  <RouterView/>
</div>
</template>

<style scoped>

</style>
