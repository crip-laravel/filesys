<template>
  <div class="file-upload blob"
       :class="{error: file.hasError}"
       :title="file.title">

    <div class="thumb no-margin">
      <img :src="file.src">
    </div>

    <div class="actions clearfix">
      <a href
         title="Start upload this file"
         class="inte-item pull-left transition-all"
         v-if="!file.hasError"
         :class="{disabled: file.$loading}"
         @click.prevent="uploadFile">Upload {{ file.name }}</a>

      <a href
         title="Delete file from queue"
         class="inte-item pull-right transition-all"
         :class="{disabled: file.$loading}"
         @click.prevent="removeUpload">&times;</a>
    </div>

  </div>
</template>

<script>
  import FileForUpload from '../../../models/FileForUpload'
  import * as mutations from '../../../store/mutations'
  import * as actions from '../../../store/actions'

  export default {
    name: 'uploads-blob',

    props: {
      file: {type: FileForUpload, required: true}
    },

    methods: {
      /**
       * Remove current file from the uploads queue.
       */
      removeUpload () {
        this.$store.commit(mutations.removeUpload, this.file)
      },

      /**
       * Upload current file to the server.
       */
      uploadFile () {
        this.$store.dispatch(actions.uploadFile, this.file)
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

  .file-upload {
    border: 1px solid $panel-default-border;
    padding: 2px;

    &.error {
      border: 1px solid $alert-danger-border;
      background-color: $alert-danger-bg;
      color: $abbr-border-color;
    }

    .inte-item {
      max-height: 28px;
      overflow: hidden;
      padding-left: 10px;
      padding-right: 10px;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }

  .grid .file-upload .inte-item {
    max-width: 170px;
  }
</style>
