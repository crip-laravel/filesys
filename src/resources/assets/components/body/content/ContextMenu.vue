<template>
  <ul class="context-menu"
      tabindex="-1"
      v-if="blob.$isContextVisible"
      :id="'blob-context-' + blob.$id"
      :style="{top: positionTop, left: positionLeft}">

    <li v-if="isDir">
      <a href class="content inte-item" @click.prevent="openBlob">
        Open folder <i>{{ blob.fullName }}</i>
      </a>
    </li>

    <li v-if="!isDir && !blob.$isSystem">
      <a href class="content inte-item" @click.prevent="openBlob">
        Select
        <span v-if="blob.mime === 'img'">image</span>
        <span v-else>file</span> {{ blob.fullName }}
      </a>
    </li>

    <li v-for="size in sizes">
      <a class="content inte-item" href
         @click.prevent="openBlob(size.url)">
        Select <i>{{ size.name }}</i> image
        <small>({{ size.width }} x {{ size.height }})</small>
      </a>
    </li>

    <li>
      <span class="content">Modified: <strong>{{ blob.$date }}</strong></span>
    </li>

    <li>
      <span class="content">Size: <strong>{{ size }}</strong></span>
    </li>
  </ul>
</template>

<script>
  import * as actions from '../../../store/actions'
  import Blob from '../../../models/Blob'
  import settings from '../../../settings'
  import sizeCalc from '../../../api/size.calculator'
  import Vue from 'vue'

  export default {
    name: 'blob-context-menu',

    props: {
      blob: {type: Blob, required: true},
      event: {type: MouseEvent, required: false}
    },

    computed: {
      /**
       * Get document height value.
       */
      htmlHeight () {
        const body = document.body
        const html = document.documentElement

        return Math.max(
          body.scrollHeight,
          body.offsetHeight,
          html.clientHeight,
          html.scrollHeight,
          html.offsetHeight)
      },

      /**
       * Get event position coordinates.
       */
      eventPosition () {
        let {x, y} = [0, 0]
        if (this.event.pageX || this.event.pageY) {
          x = this.event.pageX
          y = this.event.pageY
        } else if (this.event.clientX || this.event.clientY) {
          x = this.event.clientX + document.body.scrollLeft +
            document.documentElement.scrollLeft

          y = this.event.clientY + document.body.scrollTop +
            document.documentElement.scrollTop
        }

        return {x, y}
      },

      /**
       * Context menu y position
       */
      positionTop () {
        let {y} = this.eventPosition
        let maxHeight = this.htmlHeight - this.height - 25
        let largestHeight = y > maxHeight ? maxHeight : y
        return `${largestHeight}px`
      },

      /**
       * Context menu x position
       */
      positionLeft () {
        let {x} = this.eventPosition
        let maxWidth = window.innerWidth - this.width - 25
        let largestWidth = x > maxWidth ? maxWidth : x
        return `${largestWidth}px`
      },

      /**
       * Is current blob of directory type.
       */
      isDir () {
        return this.blob.isDir
      },

      /**
       * Get readable size content.
       */
      size () {
        return sizeCalc(this.blob.bytes)
      },

      /**
       * Determines is set strict image size for selecting image.
       * @returns {Boolean}
       */
      isStrictOutputSize () {
        return !!settings.imageSize()
      },

      /**
       * Get image sizes if they persist.
       */
      sizes () {
        // If we are limiting user for some of image size, we should not allow
        // him select any other size image.
        if (this.isStrictOutputSize) {
          return []
        }

        // Otherwise allow to select any of the configured size.
        let sizes = []

        if (this.blob.thumbs) {
          Object.keys(this.blob.thumbs).forEach(size => {
            sizes.push(this.blob.thumbs[size])
          })
        }

        return sizes
      }
    },

    data () {
      return {
        width: 0,
        height: 0
      }
    },

    methods: {
      /**
       * Open blob and allow to do it with custom url as user may use any size
       * of blob in context menu selection.
       * @param {String} url
       */
      openBlob (url) {
        if (this.isStrictOutputSize && !url) {
          url = this.blob.thumbs[settings.imageSize()].url
        }

        this.$store.dispatch(actions.openBlob, {blob: this.blob, url})
        this.hideMenu()
      },

      /**
       * Describes listener on document and in case of click outside
       * context menu, close this instance.
       * @param target
       */
      onDocumentClick ({target}) {
        let targetEq = [target.id === this.blob.$id]
        while ((target = target.parentElement)) {
          targetEq.push(target.id === this.blob.$id)
        }

        if (targetEq.filter(eq => eq).length === 0) {
          this.hideMenu()
        }
      },

      /**
       * Close menu and unbind event listener from dom.
       */
      hideMenu () {
        Vue.set(this.blob, '$isContextVisible', false)
        // unbind this listener if we close menu
        document.removeEventListener('click', this.onDocumentClick)
      }
    },

    watch: {
      'blob.$isContextVisible' (newVal) {
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

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

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
