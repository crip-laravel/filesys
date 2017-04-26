<template>
  <div>
    <label for="actions-bar-btn--file-input" class="file-input">
      <btn size="lg" icon="add">Add files</btn>
    </label>
    <input id="actions-bar-btn--file-input"
           type="file"
           name="upload-files"
           multiple="multiple"
           @change="filesAdded"/>
  </div>
</template>

<script>
  import btn from './Btn.vue'

  export default {
    name: 'add-files_actions-bar-btn',

    components: {btn},

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
        }
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

