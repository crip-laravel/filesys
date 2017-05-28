<template>
  <div>
    <label for="actions-bar-btn--file-input" class="file-input">
      <btn size="lg" icon="add">Add files</btn>
    </label>
    <input id="actions-bar-btn--file-input"
           type="file"
           name="upload-files"
           multiple="multiple"
           :accept="accept"
           @change="filesAdded"/>
  </div>
</template>

<script>
  import * as actions from '../../store/actions'
  import btn from './Btn.vue'
  import settings from '../../settings'

  export default {
    name: 'add-files_actions-bar-btn',

    components: {btn},

    computed: {
      accept () {
        switch (settings.mediaType()) {
          case settings.mediaTypes.image:
            return 'image/*'
          case settings.mediaTypes.media:
            return 'audio/*,video/*'
          case settings.mediaTypes.document:
            return 'application/*,text/*'
          default:
            return '*'
        }
      }
    },

    methods: {
      /**
       * React on file upload change event and emit upload event of added files
       * to parent component.
       * @param {Event} e
       */
      filesAdded (e) {
        // Emit default action too, for case, if we want to react somehow on
        // change event.
        this.$emit('change', e)

        let files = e.target.files || e.dataTransfer.files

        if (files.length > 0) {
          // Emit upload event only in case if we have at least one file for
          // upload selected.
          this.$emit('upload', files)

          // Add files to vuex store state queue.
          this.addUploadFiles(files)
        }
      },

      /**
       * Add files to vuex store state queue.
       * @param {FileList} files
       */
      addUploadFiles (files) {
        this.$store.dispatch(actions.addUploads, files)
      }
    }
  }
</script>

<style lang="sass" type="text/scss" scoped>
  #actions-bar-btn--file-input {
    display: none;
  }

  .file-input {
    margin: 0;
  }
</style>

