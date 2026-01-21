<script setup>
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
<div>
  <header>
    <img alt="Vue logo" class="logo" src="@/assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <nav>
        <RouterLink to="/juegos">Home</RouterLink>
        <RouterLink to="/eventos">About</RouterLink>
      </nav>
    </div>
  </header>


  <RouterView/>
</div>
</template>

<style scoped>

</style>
