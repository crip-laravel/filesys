<template>
  <div id="uploads">
    <div class="clearfix">
      <upload-file class="blob-container"
                   :file="file"
                   :key="key"
                   v-for="(file, key) in uploads">
      </upload-file>
    </div>
    <hr v-if="count > 0">
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import uploadFile from './UploadFile.vue'

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

    components: {uploadFile}
  }
</script>
