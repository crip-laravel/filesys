<template>
  <div id="uploads">
    <div class="clearfix">
      <blob class="blob-container"
            :file="file"
            :key="key"
            v-for="(file, key) in uploads">
      </blob>
    </div>
    <hr v-if="count > 0">
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import blob from './UploadBlob.vue'

  export default {
    name: 'uploads',

    computed: {
      /**
       * Store uploads queue files.
       * @return {Array}
       */
      uploads () {
        return this.$store.getters[getters.getUploads]
      },

      /**
       * Number of uploads in the queue.
       * @return {Number}
       */
      count () {
        return this.uploads.length
      }
    },

    components: {blob}
  }
</script>
