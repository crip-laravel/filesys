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
          <btn title="Rename" size="lg" icon="rename" :active="isEditEnabled" :on-click="enableEdit"
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
  import { mapGetters, mapMutations, mapActions } from 'vuex'
  import Blob from '../models/Blob'
  import btn from './ActionButton.vue'
  import * as getters from '../store/getters'
  import * as actions from '../store/actions'
  import * as mutations from '../store/mutations'
  import fileApi from '../api/file'

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
        getters.loading,
        getters.path,
        getters.display,
        getters.selectedBlob,
        getters.creating
      ]),
      isGridView () { return this.display === 'grid' },
      isListView () { return this.display === 'list' },
      isEditEnabled () { return this.selectedBlob && this.selectedBlob.$edit },
      filesForUploadCount () { return this.filesForUpload.length }
    },

    methods: {
      ...mapMutations([
        mutations.setGridView,
        mutations.setListView,
        mutations.enableEdit,
        mutations.addItem,
        mutations.selectItem,
        mutations.creatingEnabled
      ]),

      ...mapActions([
        actions.deleteBlob
      ]),

      none: _ => _,

      createFolderDialog () {
        if (!this.creating) {
          this.creatingEnabled()
          let dirToCreate = new Blob({
            $isSystem: true,
            $temp: true,
            full_name: this.path,
            name: 'New-Name',
            type: 'dir'
          })

          this.selectItem(dirToCreate)
          this.addItem(dirToCreate)
          this.enableEdit()
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
              this.addItem(blob)
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
