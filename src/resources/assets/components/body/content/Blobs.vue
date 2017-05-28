<template>
  <div id="blobs" class="row clearfix">
    <blob-new-folder></blob-new-folder>
    <blob-up></blob-up>

    <transition-group name="fade-x" tag="div">
      <div v-for="blob in content" :key="blob.$id" class="blob-container fade-x">
        <div class="context-wrapp"
             @contextmenu.prevent="openContext($event, blob)">
          <blob :blob="blob"></blob>
          <blob-context-menu :blob="blob" :event="event"></blob-context-menu>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import blob from './Blob.vue'
  import blobContextMenu from './ContextMenu.vue'
  import blobNewFolder from './BlobNewFolder.vue'
  import blobUp from './BlobUp.vue'

  export default {
    name: 'blobs',

    components: {blob, blobUp, blobNewFolder, blobContextMenu},

    computed: {
      /**
       * Get blobs from the store.
       */
      blobs () {
        return this.$store.getters[getters.getBlobs]
      },

      /**
       * Compute actual content of blobs.
       */
      content () {
        return this.blobs.sort((a, b) => {
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
      return {event: new MouseEvent('click')}
    },

    methods: {
      /**
       * Open context menu for a blob.
       * @param {MouseEvent} e
       * @param {Blob} blob
       */
      openContext (e, blob) {
        this.blobs.forEach(b => {
          b.$isContextVisible = false
        })

        blob.$isContextVisible = true
        this.event = e
      }
    }
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
