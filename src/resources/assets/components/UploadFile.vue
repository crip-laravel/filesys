<template>
  <div class="file-upload blob">
    <div class="thumb no-margin">
      <img :src="file.src">
    </div>
    <div class="actions clearfix">
      <a href="#" class="inte-item pull-left">Upload {{file.name}}</a>
      <a href="#" class="inte-item pull-right">&times;</a>
    </div>
  </div>
</template>

<script>
  import FileUpload from '../models/FileUpload'

  export default {
    name: 'file-upload',

    props: {
      file: {type: FileUpload, required: true}
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/variables";

  .file-upload {
    border: 1px solid $panel-default-border;
    padding: 2px;

    .inte-item {
      max-height: 28px;
      max-width: 170px;
      overflow: hidden;
      padding-left: 10px;
      padding-right: 10px;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }
</style>
