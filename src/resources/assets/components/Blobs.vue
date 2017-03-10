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
        return this.blobs.sort((a, b) => {
          if ((a.isDir && b.isDir) || (!a.isDir && !b.isDir)) {
            return a.name > b.name
          }

          // if types are different, make sure that dir always is first
          if (a.isDir && !b.isDir) {
            return -1
          }

          if (!a.isDir && b.isDir) {
            return 1
          }
        })
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
