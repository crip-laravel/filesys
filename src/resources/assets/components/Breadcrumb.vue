<template>
  <div>Breadcrumb "{{path}}"</div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as getters from '../store/getters'

  export default {
    name: 'breadcrumb',

    mounted () {
      console.log(`${this._name} mounted`, {path: this.path})
    },

    computed: {
      ...mapGetters([getters.path])
    }
  }
</script>
