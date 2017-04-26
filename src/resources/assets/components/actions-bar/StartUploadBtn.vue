<template>
  <div v-if="hasUploads">
    <btn size="lg" icon="upload" @click="upload">Upload {{count}} files</btn>
  </div>
</template>

<script>
  import btn from './Btn.vue'
  import { startUpload } from './../../store/actions'

  export default {
    name: 'start-upload_actions-bar-btn',

    components: {btn},

    props: {
      /**
       * Count of files to be uploaded.
       * @returns {Number}
       */
      count: {type: Number, required: true}
    },

    computed: {
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
        this.$store.dispatch(startUpload)
      }
    }
  }
</script>

