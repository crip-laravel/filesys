<template>
  <div class="file-upload blob" :class="{error: file.hasError}"
       :title="file.title">
    <div class="thumb no-margin">
      <img :src="file.src">
    </div>
    <div class="actions clearfix">
      <a href="#" class="inte-item pull-left"
         :class="{disabled: file.$loading}"
         @click.prevent="uploadFile(file)">Upload {{file.name}}</a>

      <a href="#" class="inte-item pull-right"
         :class="{disabled: file.$loading}"
         @click.prevent="removeUpload(file)">&times;</a>
    </div>
  </div>
</template>

<script>
  import FileUpload from '../../../models/FileForUpload'
  import * as mutations from '../../../store/mutations'
  import * as actions from '../../../store/actions'
  import { mapActions, mapMutations } from 'vuex'

  export default {
    name: 'file-upload',

    props: {
      file: {type: FileUpload, required: true}
    },

    methods: {
      ...mapMutations([
        mutations.removeUpload
      ]),

      ...mapActions([
        actions.uploadFile
      ])
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

  .file-upload {
    border: 1px solid $panel-default-border;
    padding: 2px;

    &.error {
      border: 1px solid $brand-danger;
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
