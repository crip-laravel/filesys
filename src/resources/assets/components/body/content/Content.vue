<template>
  <div id="blobs">

    <div class="row clearfix" :class="[displayType]">
      <uploads></uploads>
    </div>

    <div class="row clearfix" :class="[displayType]">

      <blob-new-folder></blob-new-folder>

      <blob-up></blob-up>

      <div v-for="blob in content" :key="blob.$id" class="blob-container">
        <div class="context-wrapp"
             @contextmenu.prevent="openMenu($event, blob.$id)">

          <blob :blob="blob"></blob>

          <blob-context-menu :is-visible="isVisible(blob.$id)"
                             :blob="blob"
                             :top="top"
                             :left="left"
                             @close="closeMenu"></blob-context-menu>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import blob from './Blob.vue'
  import blobUp from './BlobUp.vue'
  import blobNewFolder from './BlobNewFolder.vue'
  import blobContextMenu from './ContextMenu.vue'
  import settings from './../../../settings'
  import uploads from './../uploads/Uploads.vue'
  import Vue from 'vue'

  export default {
    name: 'content-section',

    computed: {
      blobs () {
        return this.$store.getters[getters.getBlobs]
      },

      displayType () {
        return this.$store.getters[getters.getDisplayType]
      },

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
       * @param {string} id
       * @return {Boolean}
       */
      isVisible (id) {
        return !!this.viewMenu[id]
      },

      /**
       * Close menu for blob with index.
       * @param {String} id
       */
      closeMenu (id) {
        Vue.set(this.viewMenu, id, false)
      },

      /**
       * @param {MouseEvent} e
       * @param {String} id
       */
      openMenu (e, id) {
        this.top = e.y
        this.left = e.x

        // before open, make sure all other are closed
        Object.keys(this.viewMenu).forEach((key) => {
          return !this.viewMenu[key] || Vue.set(this.viewMenu, key, false)
        })

        Vue.set(this.viewMenu, id, true)
      }
    },

    components: {blob, blobUp, blobNewFolder, blobContextMenu, uploads}
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

  .grid .blob-container {
    float: left;
    width: 205px;
    height: 139px;
    margin: 3px;
  }

  #blobs {
    border-top: 1px solid $menu-border-color;
  }

  .blob {
    overflow: hidden;

    .thumb {
      height: 105px;
      margin-bottom: 8px;
      overflow: hidden;

      img {
        display: block;
        margin: 0 auto 6px auto;
        max-height: 100px;
      }
    }
  }

  .list .blob {
    .thumb {
      border: none;
      float: left;
      height: auto;
      margin: 4px 0 4px 4px;
      padding: 0;
      text-align: center;
      width: 50px;

      img {
        height: 25px;
        margin: 0 auto;
      }
    }
  }
</style>
