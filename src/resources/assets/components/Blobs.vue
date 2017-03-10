<template>
  <div id="blobs">
    <div class="row" :class="[display]">
      <div v-for="blob in content" class="blob-container">
        <blob :blob="blob"></blob>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import blob from './helpers/Blob.vue'
  import * as getters from '../store/getters'

  export default {
    name: 'blobs',

    mounted () {
      console.log(`${this._name} mounted`, {blobs: this.blobs, path: this.path})
    },

    computed: {
      ...mapGetters([getters.path, getters.blobs, getters.display]),
      content () {
        // TODO: implement blob sort functionality
        return this.blobs
      }
    },

    components: {blob}
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  .grid .blob-container {
    display: inline-block;
    width: 205px;
    height: 160px;
    margin: 3px;
  }
</style>
