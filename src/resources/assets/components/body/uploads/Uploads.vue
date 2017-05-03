<template>
  <div id="uploads">
    <div class="clearfix">
      <upload-file :file="file" class="blob-container"
                   :key="key"
                   v-for="(file, key) in uploads"></upload-file>
    </div>
    <hr v-if="!!uploadsCount">
  </div>
</template>

<script>
  import uploadFile from './UploadFile.vue'
  import * as getters from '../../../store/getters'
  import { mapGetters } from 'vuex'

  export default {
    name: 'uploads',

    computed: {
      ...mapGetters([
        getters.uploads,
        getters.uploadsCount
      ])
    },

    components: {uploadFile}
  }
</script>
