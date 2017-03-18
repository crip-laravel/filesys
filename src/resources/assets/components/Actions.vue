<template>
  <div class="row">
    <div class="manager-actions clearfix">
      <div class="group">
        <div class="col">
          <label for="file-input">
            <btn title="Add files" size="lg" icon="add" :on-click="none"></btn>
          </label>
          <input id="file-input" type="file" multiple @change="filesForUploadAdded"/>
        </div>
        <div class="col" v-if="filesForUploadCount">
          <btn :title="`Upload ${filesForUploadCount} files`" size="lg" icon="upload" :on-click="startUpload"></btn>
        </div>
        <div class="col">
          <btn title="Create Folder" size="lg" icon="add-folder" :on-click="createFolderDialog"
               :active="creating"></btn>
        </div>
      </div>
      <div class="group">
        <div class="col">
          <btn title="Grid view" size="sm" :on-click="setGridView" :active="isGridView"></btn>
          <btn title="List view" size="sm" :on-click="setListView" :active="isListView"></btn>
        </div>
      </div>
      <div class="group">
        <div class="col">
          <btn title="Rename" size="lg" icon="rename" :active="isEditEnabled" :on-click="setBlobEditMode"
               :disabled="!selectedBlob"></btn>
        </div>
        <div class="col">
          <btn title="Delete" size="lg" icon="delete" :on-click="deleteBlob" :disabled="!selectedBlob"></btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import * as actions from '../store/actions'
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'
  import Blob from '../models/Blob'
  import btn from './ActionButton.vue'
  import fileApi from '../api/file'
  import { mapGetters, mapMutations, mapActions } from 'vuex'

  export default {
    name: 'actions',

    data () {
      return {
        isCreating: false,
        filesForUpload: []
      }
    },

    computed: {
      ...mapGetters([
        getters.creating,
        getters.isEditEnabled,
        getters.isGridView,
        getters.isListView,
        getters.path,
        getters.selectedBlob
      ]),
      filesForUploadCount () { return this.filesForUpload.length }
    },

    methods: {
      ...mapMutations([
        mutations.setBlobEditMode,
        mutations.setCreateEnabled,
        mutations.setGridView,
        mutations.setListView,
        mutations.setNewBlob,
        mutations.setSelectedBlob
      ]),

      ...mapActions([
        actions.deleteBlob
      ]),

      none: _ => _,

      createFolderDialog () {
        if (!this.creating) {
          this.setCreateEnabled()
          let dirToCreate = new Blob({
            $isSystem: true,
            $temp: true,
            full_name: this.path,
            name: 'New-Name',
            type: 'dir'
          })

          this.setSelectedBlob(dirToCreate)
          this.setNewBlob(dirToCreate)
          this.setBlobEditMode()
        }
      },

      filesForUploadAdded (event) {
        let files = event.target.files || event.dataTransfer.files
        if (files.length < 1) {
          return
        }

        for (let key in files) {
          if (files.hasOwnProperty(key)) {
            this.filesForUpload.push(files[key])
          }
        }
      },

      startUpload () {
        this.filesForUpload.forEach(file => {
          fileApi.upload(this.path, file)
            .then(blob => {
              this.filesForUpload.splice(this.filesForUpload.indexOf(file), 1)
              this.setNewBlob(blob)
            })
        })
      }
    },

    components: {btn}
  }
</script>

<style lang="sass" rel="stylesheet/scss">
  @import "../sass/variables";

  .manager-actions {
    border-bottom: 1px solid $second-color;
    width: 100%;

    .group {
      margin: 4px 0;
      border-right: 1px solid lighten($second-color, 20%);
      padding: 0 6px;
    }

    .col, .group {
      height: 100px;
      float: left;
    }

    #file-input {
      display: none;
    }
  }
</style>
