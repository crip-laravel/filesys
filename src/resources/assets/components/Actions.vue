<template>
  <div class="row">
    <div class="manager-actions clearfix">
      <div class="group">
        <div class="col">
          <btn title="Upload" size="lg" :on-click="openUploadFileDialog"></btn>
        </div>
        <div class="col">
          <btn title="Create Folder" size="lg" :on-click="createFolderDialog"></btn>
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
          <btn title="Edit" size="lg" :on-click="enableEditSelectedBlob" :disabled="!selectedBlob"></btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import btn from './helpers/ActionButton.vue'
  import folderApi from '../api/folder'
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'

  export default {
    name: 'actions',

    mounted () {
      console.log(`${this._name} mounted`)
    },

    computed: {
      ...mapGetters([getters.loading, getters.path, getters.display, getters.selectedBlob]),
      isGridView () { return this.display === 'grid' },
      isListView () { return this.display === 'list' }
    },

    methods: {
      createFolderDialog () {
        console.log('createFolderDialog()')
      },

      createFolder (name) {
        console.log('createFolder()', {folder: this.path, name})
        folderApi.create(this.path, name)
          .then(({data}) => {
            this.$store.commit(mutations.addItem, {item: data})
          })
      },

      openUploadFileDialog () {
        console.log('openUploadFileDialog()')
      },

      setGridView () {
        this.$store.commit(mutations.setGridView)
      },

      setListView () {
        this.$store.commit(mutations.setListView)
      },

      enableEditSelectedBlob () {
        if (this.selectedBlob) {
          this.selectedBlob.$edit = true
        }
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
  }

</style>
