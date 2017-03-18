<template>
  <div id="blobs">
    <div class="row clearfix" :class="[displayType]">
      <div v-for="blob in content" class="blob-container">
        <div @contextmenu="openMenu" class="context-wrapp">
          <blob :blob="blob"></blob>
          <ul class="context-menu" tabindex="-1" v-if="viewMenu" :style="{top:top, left:left}"
              @blur="closeMenu">
            <li><a href="#" @click="select(blob)">Select</a></li>
            <li>Second list item</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import * as getters from '../store/getters'
  import blob from './Blob.vue'
  import settings from './../settings'
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
            return a.name > b.name
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
        viewMenu: false,
        top: '0px',
        left: '0px'
      }
    },

    methods: {
      /**
       * @param {Number} top
       * @param {Number} left
       * @param el
       */
      setMenu (top, left, el) {
        let largestHeight = window.innerHeight - el.offsetHeight - 25
        let largestWidth = window.innerWidth - el.offsetWidth - 25

        if (top > largestHeight) top = largestHeight
        if (left > largestWidth) left = largestWidth

        this.top = top + 'px'
        this.left = left + 'px'
      },

      /**
       * Close menu
       */
      closeMenu () {
        this.viewMenu = false
      },

      /**
       * @param {MouseEvent} e
       */
      openMenu (e) {
        let wrapp = this.findAncestor(e.target, 'context-wrapp')
        this.viewMenu = true

        // interact with element only when it is binded to dom
        Vue.nextTick(() => {
          let el = this.findChild(wrapp, 'context-menu')
          el.focus()
          this.setMenu(e.y, e.x, el)
        })

        e.preventDefault()
      },

      /**
       * Select blob
       * @param {Blob} blob
       * @param {String} size ['default']
       */
      select (blob, size = 'default') {
        // TODO: method should be in blob store actions
      },

      /**
       * @param el
       * @param {string} cls
       * @returns {*}
       */
      findAncestor (el, cls) {
        while ((el = el.parentElement) && !el.classList.contains(cls)) {}
        return el
      },

      /**
       * @param el
       * @param {string} cls
       * @returns {*}
       */
      findChild (el, cls) {
        for (let i = 0; i < el.childNodes.length; i++) {
          if (el.childNodes.hasOwnProperty(i) &&
            el.childNodes[i].classList &&
            el.childNodes[i].classList.contains(cls)) {
            return el.childNodes[i]
          }
        }
      }
    },

    components: {blob}
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
    border-top: 1px solid $second-color;
  }

  .context-menu {
    background: #FAFAFA;
    border: 1px solid $laravel-border-color;
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);
    display: block;
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    width: 250px;
    z-index: 999999;

    li {
      border-bottom: 1px solid $laravel-border-color;
      margin: 0;
      padding: 5px 35px;

      &:last-child {
        border-bottom: none;
      }

      &:hover {
        background: $brand-primary;
        color: $text-color;
      }
    }
  }
</style>
