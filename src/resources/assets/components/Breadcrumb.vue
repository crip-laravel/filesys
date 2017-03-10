<template>
  <div>Breadcrumb "{{path}}"</div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as getters from '../store/getters'

  export default {
    name: 'breadcrumb',

    computed: {
      ...mapGetters([getters.path])
    }
  }
</script>
