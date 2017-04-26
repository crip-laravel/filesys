<template>
  <div class="row">
    <div class="manager-actions clearfix">
      <div class="group">
        <add-files-btn class="col" @upload="addUploadFiles"></add-files-btn>
        <start-upload-btn class="col" :count="uploadsCount"></start-upload-btn>

        <div class="col">
          <btn title="Create Folder" size="lg" icon="add-folder"
               :on-click="openCreateFolderDialog"
               :active="creating"></btn>
        </div><!-- /.col Create folder btn -->
      </div>
      <div class="group">
        <div class="col">
          <btn title="Grid view" size="sm" :on-click="setGridView"
               :active="isGridView"></btn>
          <btn title="List view" size="sm" :on-click="setListView"
               :active="isListView"></btn>
        </div>
      </div>
      <div class="group">
        <div class="col">
          <btn title="Rename" size="lg" icon="rename" :active="isEditEnabled"
               :on-click="startEditBlob"
               :disabled="isRenameDisabled"></btn>
        </div>
        <div class="col">
          <btn title="Delete" size="lg" icon="delete" :on-click="deleteBlob"
               :disabled="isDeleteDisabled"></btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import * as actions from '../store/actions'
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'
  import btn from './ActionButton.vue'
  import { mapGetters, mapMutations, mapActions } from 'vuex'
  import addFilesBtn from './actions-bar/AddFilesBtn.vue'
  import startUploadBtn from './actions-bar/StartUploadBtn.vue'

  export default {
    name: 'actions',

    data () {
      return {
        filesForUpload: []
      }
    },

    computed: {
      ...mapGetters([
        getters.creating,
        getters.isEditEnabled,
        getters.isGridView,
        getters.isListView,
        getters.selectedBlob,
        getters.uploadsCount
      ]),

      isDeleteDisabled () {
        return !this.selectedBlob || this.creating
      },

      isRenameDisabled () {
        return !this.selectedBlob || this.creating
      }
    },

    methods: {
      ...mapMutations([
        mutations.setGridView,
        mutations.setListView
      ]),

      ...mapActions([
        actions.openCreateFolderDialog,
        actions.filesForUploadAdded,
        actions.startUpload
      ]),

      /**
       * Add upload files to Vuex queue.
       * @param {FileList} files
       */
      addUploadFiles (files) {
        this.filesForUploadAdded(files)
      },

      /**
       * Initialize state to make available blob rename functionality.
       */
      startEditBlob () {
        if (!this.isRenameDisabled) {
          this.$store.dispatch(actions.startEditBlob)
        }
      },

      /**
       * Delete selected blob.
       */
      deleteBlob () {
        if (!this.isDeleteDisabled) {
          this.$store.dispatch(actions.deleteBlob)
        }
      },

      /**
       * Do nothing.
       * @param _
       */
      noop: _ => _
    },

    components: {btn, addFilesBtn, startUploadBtn}
  }
</script>

<style lang="sass" type="text/scss">
  @import "../sass/variables";

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
