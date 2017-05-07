<template>
  <div class="blob-container" v-if="isNotRootPath">
    <blob :blob="blob"></blob>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import Blob from '../../../models/Blob'
  import blob from './Blob.vue'
  import settings from './../../../settings'

  export default {
    name: 'blob-up',

    computed: {
      /**
       * Determines is the current state path equals root path.
       * @return {Boolean}
       */
      isNotRootPath () {
        return this.$store.getters[getters.getPath] !== ''
      },

      /**
       * Get blob instance for path up.
       * @return {Blob}
       */
      blob () {
        const pathUp = this.$store.getters[getters.getPathUp]

        return new Blob({
          fullName: '..',
          type: 'dir',
          path: pathUp,
          thumb: settings.dirIcon,
          mediaType: settings.mediaTypes.dir
        })
      }
    },

    components: {blob}
  }
</script>
