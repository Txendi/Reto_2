<script setup>
import { ref } from 'vue'

const titulo = ref('')
const descripcion = ref('')
const fecha = ref('')
const hora = ref('')
const plazasLibres = ref('')
const tipo = ref('')

const crearEvento = () => {
  const evento = {
    titulo: titulo.value,
    descripcion: descripcion.value,
    fecha: fecha.value,
    hora: hora.value,
    plazasLibres: Number(plazasLibres.value),
    tipo: tipo.value,
  }

  console.log('EVENTO:', evento)
}
</script>



<template>
  <section>
    <h1>Crear evento</h1>

    <form @submit.prevent="crearEvento">
      <input type="text" placeholder="Título" v-model="titulo" />
      <textarea placeholder="Descripción" v-model="descripcion"></textarea>
      <input type="date" v-model="fecha" />
      <input type="time" v-model="hora" />
      <input type="number" placeholder="Plazas" v-model="plazasLibres" />

      <select v-model="tipo">
        <option value="">Selecciona tipo</option>
        <option>Charla</option>
        <option>Taller</option>
        <option>Torneo</option>
        <option>Exhibición</option>
      </select>

      <button type="submit">Crear</button>
    </form>
  </section>
</template>


