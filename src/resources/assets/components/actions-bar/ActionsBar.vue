<template>
  <div class="row">
    <div class="manager-actions clearfix">
      <div class="group">
        <add-files-btn class="col"></add-files-btn>
        <start-upload-btn class="col"></start-upload-btn>
        <create-folder-btn class="col"></create-folder-btn>
      </div>
      <div class="group">
        <div class="col">
          <change-view-btn view="grid">Grid view</change-view-btn>
          <change-view-btn view="list">List view</change-view-btn>
        </div>
      </div>
      <div class="group">
        <div class="col">
          <change-filter-btn filter="image" title="View only images">Images</change-filter-btn>
          <change-filter-btn filter="media" title="View only media files">Media</change-filter-btn>
          <change-filter-btn filter="document" title="View only documents">Docs</change-filter-btn>
        </div>
      </div>
      <div class="group">
        <rename-btn class="col"></rename-btn>
        <delete-btn class="col"></delete-btn>
      </div>
    </div>
  </div>
</template>

<script>
  import addFilesBtn from './AddFilesBtn.vue'
  import btn from './Btn.vue'
  import changeFilterBtn from './ChangeFilterBtn.vue'
  import changeViewBtn from './ChangeViewBtn.vue'
  import createFolderBtn from './CreateFolderBtn.vue'
  import deleteBtn from './DeleteBtn.vue'
  import renameBtn from './RenameBtn.vue'
  import startUploadBtn from './StartUploadBtn.vue'

  export default {
    name: 'actions-bar',

    components: {
      addFilesBtn,
      btn,
      changeFilterBtn,
      changeViewBtn,
      createFolderBtn,
      deleteBtn,
      renameBtn,
      startUploadBtn
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../sass/variables";

  .manager-actions {
    border-bottom: 2px solid $menu-border-color;
    background-color: $menu-bg;

    .group {
      border-right: 1px solid $menu-border-color;
    }

    .col, .group {
      float: left;
      min-height: 58px;

      @media (min-width: $screen-sm-min) {
        .col, .group {
          min-height: 88px;
        }
      }
    }
  }
</style>
