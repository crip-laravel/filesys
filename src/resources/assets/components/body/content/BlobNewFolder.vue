<template>
  <div class="blob-container" v-if="isNewFolderEnabled">
    <blob :blob="blob"></blob>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import Blob from '../../../models/Blob'
  import blob from './Blob.vue'
  import settings from './../../../settings'

  export default {
    name: 'blob-new-folder',

    computed: {
      /**
       * Determines is the current state enables new folder creation.
       * @return {Boolean}
       */
      isNewFolderEnabled () {
        return this.$store.getters[getters.getCreateFolderBlobVisibility]
      },

      /**
       * Get blob instance for new folder.
       * @return {Blob}
       */
      blob () {
        return new Blob({
          type: 'dir',
          thumb: settings.dirIcon,
          mediaType: settings.mediaTypes.dir,

          $newName: 'new folder',
          $rename: true,
          $selected: true,
          $temp: true
        })
      }
    },

    components: {blob}
  }
</script>
