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
        <rename-btn class="col"></rename-btn>
        <delete-btn class="col"></delete-btn>
      </div>
    </div>
  </div>
</template>

<script>
  import btn from './Btn.vue'
  import addFilesBtn from './AddFilesBtn.vue'
  import startUploadBtn from './StartUploadBtn.vue'
  import createFolderBtn from './CreateFolderBtn.vue'
  import renameBtn from './RenameBtn.vue'
  import deleteBtn from './DeleteBtn.vue'
  import changeViewBtn from './ChangeViewBtn.vue'

  export default {
    name: 'actions-bar',

    components: {
      btn,
      addFilesBtn,
      startUploadBtn,
      createFolderBtn,
      renameBtn,
      deleteBtn,
      changeViewBtn
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
