<template>
  <div id="blobs">

    <div class="row clearfix">
      <uploads></uploads>
    </div>

    <div class="row clearfix" :class="[displayType]">
      <div v-for="(blob, index) in content" class="blob-container">
        <div @contextmenu.prevent="openMenu($event, index)"
             class="context-wrapp">
          <blob :blob="blob"></blob>
          <blob-context-menu :is-visible="isVisible(index)" :index="index"
                             :blob="blob"
                             :top="top" :left="left"
                             @close="closeMenu"></blob-context-menu>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import * as getters from '../store/getters'
  import blob from './Blob.vue'
  import blobContextMenu from './BlobContextMenu.vue'
  import settings from './../settings'
  import uploads from './Uploads.vue'
  import Vue from 'vue'
  import { mapGetters } from 'vuex'

  export default {
    name: 'blobs',

    computed: {
      ...mapGetters([
        getters.path,
        getters.blobs,
        getters.displayType
      ]),

      /**
       * Compute actual content of blobs.
       */
      content () {
        let filtered = this.blobs

        if (settings.mediaType() !== settings.mediaTypes.file) {
          const consistent = [settings.mediaTypes.dir, settings.mediaType()]
          filtered = this.blobs.filter((blob) => {
            return ~consistent.indexOf(blob.mediatype)
          })
        }

        return filtered.sort((a, b) => {
          if ((a.isDir && b.isDir) || (!a.isDir && !b.isDir)) {
            if (a.name < b.name) return -1
            if (a.name > b.name) return 1
            return 0
          }

          // if types are different, make sure that dir always is first
          if (a.isDir && !b.isDir) {
            return -1
          }

          if (!a.isDir && b.isDir) {
            return 1
          }
        })
      }
    },

    data () {
      return {
        viewMenu: {},
        top: 0,
        left: 0
      }
    },

    methods: {
      /**
       * Determines is context menu visible for this index blob.
       * @param {Number} index
       * @return {Boolean}
       */
      isVisible (index) {
        return !!this.viewMenu[index]
      },

      /**
       * Close menu for blob with index.
       * @param {Number} index
       */
      closeMenu (index) {
        Vue.set(this.viewMenu, index, false)
      },

      /**
       * @param {MouseEvent} e
       * @param {Number} index
       */
      openMenu (e, index) {
        this.top = e.y
        this.left = e.x

        // before open, make sure all other are closed
        Object.keys(this.viewMenu).forEach(key => Vue.set(this.viewMenu, key, false))

        Vue.set(this.viewMenu, index, true)
      }
    },

    components: {blob, blobContextMenu, uploads}
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/variables";

  .grid .blob-container {
    float: left;
    width: 205px;
    height: 139px;
    margin: 3px;
  }

  #blobs {
    border-top: 1px solid $menu-border-color;
  }
</style>
