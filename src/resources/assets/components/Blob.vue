<template>
  <div class="blob" :class="classes" @click="selectItem(blob)" :title="title">
    <div class="thumb thumbnail" @dblclick="openItem(blob)">
      <img :src="blob.thumb">
    </div>
    <div v-if="blob.$edit">
      <form @submit.prevent="save">
        <input name="name" :id="blob.$id" v-model="blob.newName">
      </form>
    </div>
    <div v-else class="blob-description" @dblclick="enableEdit()">
      {{title}}
    </div>
  </div>
</template>

<script>
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'
  import Blob from '../models/Blob'
  import settings from './../settings'
  import { changePath, saveBlob } from '../store/actions'
  import { mapGetters, mapMutations, mapActions } from 'vuex'

  export default {
    name: 'blob',

    props: {
      blob: {type: Blob}
    },

    computed: {
      ...mapGetters([getters.selectedBlob]),
      classes () { return {active: this.selectedBlob === this.blob} },
      title () { return this.blob.isDir ? this.blob.name : this.blob.full_name }
    },

    methods: {
      ...mapMutations([
        mutations.selectItem,
        mutations.enableEdit
      ]),

      ...mapActions([
        changePath,
        saveBlob
      ]),

      save () {
        this.saveBlob(this.blob)
      },

      /**
       * Open folder or file
       * @param {Blob} blob
       */
      openItem (blob) {
        if (blob.isDir) {
          return this[changePath](blob.full_name)
        }

        if (settings.target() === 'tinymce') {
          return this.selectForTinyMce(blob)
        }

        return this.selectForCallback(blob)
      },

      /**
       * Select file for tinyMCE
       * @param {Blob} blob
       */
      selectForTinyMce (blob) {
        if (!top.tinymce) {
          throw new Error('tinyMCE is selected as target, but `window.top` does not contain it!')
        }

        let url = this.resolveUrl(blob)
        let wManager = top.tinymce.activeEditor.windowManager

        if (top.tinymce.majorVersion < 4) {
          wManager.params.setUrl(url)
          wManager.close(wManager.params.mce_window_id)
        } else {
          wManager.getParams().setUrl(url)
          wManager.close()
        }
      },

      /**
       * Select url for user callback
       * @param {Blob} blob
       */
      selectForCallback (blob) {
        let userCallback = settings.callback()
        let callback = _ => _

        if (userCallback) {
          callback = window[userCallback] || parent[userCallback] || top[userCallback]
        } else {
          callback = cripFilesystemManager || parent.cripFilesystemManager || top.cripFilesystemManager
        }

        if (typeof callback !== 'function') {
          throw new Error('callback method for file select not found!')
        }

        callback(this.resolveUrl(blob), settings.params)
      },

      /**
       * TODO: should be option to choose sized image
       * @param {Blob} blob
       */
      resolveUrl (blob) {
        return blob.url
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/_variables";

  .blob {
    overflow: hidden;

    &.active {
      background-color: darken($footer-text-color, 10%);
      border-color: $second-color;
      color: $link-color;
    }

    &:hover {
      background-color: darken($footer-text-color, 20%);
      border-color: $second-color;
      color: $link-color;
      cursor: pointer;
    }

    .thumb {
      height: 105px;
      overflow: hidden;
      margin-bottom: 8px;

      img {
        margin-bottom: 6px;
        max-height: 100px;
      }
    }

    input {
      color: $brand-primary;
      width: 100%;
      padding: 0 5px;
      outline: none;
    }

    .blob-description {
      height: 28px;
      margin: 0 4px;
      text-align: center;
    }
  }

  .list .blob {
    border-bottom: 1px solid $second-color;

    .thumb {
      float: left;
      height: auto;
      margin: 4px 0 4px 4px;
      padding: 0;
      text-align: center;
      width: 50px;
      border: none;

      img {
        height: 25px;
        margin: 0 auto;
      }
    }

    .blob-description, form {
      float: left;
      margin: 5px 0 0 10px;
    }

    form {
      margin-top: 3px;
      min-width: 218px;
      width: 50%;
    }
  }
</style>
