<template>
  <transition name="fade-y">
    <div v-if="hasUploads" class="fade-y">
      <btn size="lg" icon="upload" @click="upload">Upload {{count}} files</btn>
    </div>
  </transition>
</template>

<script>
  import btn from './Btn.vue'
  import * as getters from '../../store/getters'
  import * as actions from './../../store/actions'

  export default {
    name: 'start-upload_actions-bar-btn',

    components: {btn},

    computed: {
      /**
       * Count of files to be uploaded.
       * @returns {Number}
       */
      count () {
        return this.$store.getters[getters.getUploadsCount]
      },

      /**
       * Determines is in count property any file for upload.
       * @returns {Boolean} Returns <c>false</c> if there is no items in the
       * uploads queue.
       */
      hasUploads () {
        return this.count > 0
      }
    },

    methods: {
      /**
       * Start upload files from the queue.
       */
      upload () {
        this.$store.dispatch(actions.uploadAllFiles)
      }
    }
  }
</script>

