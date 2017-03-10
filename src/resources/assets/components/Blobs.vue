<template>
  <div id="blobs">
    <div class="row clearfix" :class="[display]">
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
    float: left;
    width: 205px;
    height: 134px;
    margin: 3px;
  }
</style>
