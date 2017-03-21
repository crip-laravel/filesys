<template>
  <ul class="context-menu" :id="id(index)" tabindex="-1" v-if="isVisible"
      :style="{top: posTop, left: posLeft}">

    <li v-if="isDir">
      <a class="content inte-item" href="#" @click.prevent="open(blob)">Open folder <i>{{blob.name}}</i></a>
    </li>

    <li v-if="!isDir && !blob.$isSystem">
      <a href="#" class="content inte-item" @click.prevent="open(blob)">
        Select <span v-if="blob.mime === 'img'">image <small>({{blob.size[0]}} x {{blob.size[1]}})</small></span>
        <span v-else>file</span>
      </a>
    </li>

    <li v-for="size in sizes">
      <a class="content inte-item" href="#" @click.prevent="open(blob, size.url)">
        Select <i>{{size.name}}</i> image
        <small>({{size.x}} x {{size.y}})</small>
      </a>
    </li>

    <li v-if="!blob.$isSystem"><span class="content">Modified: <strong>{{blob.$date}}</strong></span></li>
    <li v-if="!blob.$isSystem"><span class="content">Size: <strong>{{size}}</strong></span></li>
  </ul>
</template>

<script>
  import * as actions from '../store/actions'
  import Blob from '../models/Blob'
  import Vue from 'vue'
  import { mapActions } from 'vuex'

  export default {
    name: 'blob-context-menu',

    props: {
      isVisible: {type: Boolean, required: true},
      index: {type: Number, required: true},
      blob: {type: Blob, required: true},
      top: {type: Number, required: true},
      left: {type: Number, required: true}
    },

    computed: {
      // context menu y position
      maxHeight () { return window.innerHeight - this.height - 25 },
      largestHeight () { return this.top > this.maxHeight ? this.maxHeight : this.top },
      posTop () { return `${this.largestHeight}px` },

      // context menu x position
      maxWidth () { return window.innerWidth - this.width - 25 },
      largestWidth () { return this.left > this.maxWidth ? this.maxWidth : this.left },
      posLeft () { return `${this.largestWidth}px` },

      size () {
        if (this.blob.bytes === 1) {
          return this.blob.bytes + ' byte'
        }

        if (this.blob.bytes === 0) {
          return '0 bytes'
        }

        let fileSizes = [
          {val: 1, postfix: ' bytes'},
          {val: 1024, postfix: ' kB'},
          {val: 1048576, postfix: ' MB'},
          {val: 1073741824, postfix: ' GB'}
        ]

        let result = ''
        fileSizes.forEach(size => {
          if (this.blob.bytes >= size.val) {
            result = (this.blob.bytes / size.val).toFixed(2) + size.postfix
          }
        })

        return result
      }
    },

    data () {
      let sizes = []

      if (this.blob.thumbs) {
        Object.keys(this.blob.thumbs).forEach(size => {
          let thumb = this.blob.thumbs[size]
          sizes.push({
            name: size,
            x: thumb.size[0],
            y: thumb.size[1],
            url: thumb.url
          })
        })
      }

      return {
        width: 0,
        height: 0,
        isDir: this.blob.isDir,
        sizes
      }
    },

    methods: {
      ...mapActions([
        actions.openBlob
      ]),

      id (index) {
        return `context-menu-${index}`
      },

      /**
       * Open blob wrapper method to call hide on selecting blob.
       * @param {Blob} blob
       * @param {String} url
       */
      open (blob, url) {
        this.openBlob({blob, url})
        this.hideMenu()
      },

      /**
       * Describes listener on document and in case of click outside
       * context menu, close this instance.
       * @param target
       */
      onDocumentClick ({target}) {
        let menuId = this.id(this.index)
        let targetEq = [target.id === menuId]
        while ((target = target.parentElement)) {
          targetEq.push(target.id === menuId)
        }

        if (targetEq.filter(eq => eq).length === 0) {
          this.hideMenu()
        }
      },

      /**
       * Close menu and unbind event listener from dom.
       */
      hideMenu () {
        this.$emit('close', this.index)
        // unbind this listener if we close menu
        document.removeEventListener('click', this.onDocumentClick)
      }
    },

    watch: {
      isVisible (newVal) {
        if (newVal) {
          Vue.nextTick(() => {
            // Ret element sizes to calculate correct position only after element
            // becomes visible in dom and this mutation is applied
            this.width = this.$el.offsetWidth
            this.height = this.$el.offsetHeight
          })

          // Register listener on document and in case of click outside
          // context menu, close this instance
          document.addEventListener('click', this.onDocumentClick.bind(this))
        }
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/variables";

  .context-menu {
    background: $menu-bg;
    border: 1px solid $menu-border-color;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);
    display: block;
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    width: 250px;
    z-index: 999999;

    li {
      border-bottom: 1px solid $menu-border-color;
      margin: 0;

      &:last-child {
        border-bottom: none;
      }

      .content {
        display: block;
        padding: 5px;
      }
    }
  }
</style>
