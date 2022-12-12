<template>
  <q-page class="">
<!--    <pre>{{store.user}}</pre>-->
  </q-page>
</template>

<script>
import { useCounterStore } from 'stores/example-store'

export default {
  name: 'IndexPageItem',
  data () {
    return {
      store: useCounterStore()
    }
  },
  methods: {
  }
}
</script>
