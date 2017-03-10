<template>
  <div class="row">
    <div class="manager-actions">
      <div class="group">
        <div class="col">
          <btn title="Upload" size="lg" :on-click="openUploadFileDialog"></btn>
        </div>
        <div class="col">
          <btn title="Create Folder" size="lg" :on-click="createFolder"></btn>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import btn from './helpers/ActionButton.vue'
  import * as getters from '../store/getters'

  export default {
    name: 'actions',

    mounted () {
      console.log(`${this._name} mounted`)
    },

    computed: {
      ...mapGetters([getters.loading])
    },

    methods: {
      createFolder () {
        console.log('createFolder()')
      },

      openUploadFileDialog () {
        console.log('openUploadFileDialog()')
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
      display: inline-block;
    }
  }

</style>
