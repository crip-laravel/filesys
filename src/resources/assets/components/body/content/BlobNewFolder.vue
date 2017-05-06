<template>
  <div class="blob-container" v-if="isNewFolderEnabled">
    <blob :blob="blob"></blob>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import blob from './Blob.vue'

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
        return this.$store.getters[getters.getNewFolder]
      }
    },

    components: {blob}
  }
</script>
