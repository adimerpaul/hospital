<template>
  <q-item clickable v-ripple to="/schedule" active-class="bg-blue-6 text-white">
    <q-item-section avatar>
      <q-icon name="o_schedule" />
    </q-item-section>
    <q-item-section>
      Horarios
    </q-item-section>
  </q-item>
</template>
<script>
export default {
  name: 'MenuOptionItem'
}
</script>

<style scoped>

</style>
