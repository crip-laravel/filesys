<template>
  <div id="blobs">
    <div class="row clearfix" :class="[displayType]">
      <div v-for="(blob, index) in content" class="blob-container">
        <div @contextmenu.prevent="openMenu($event, index)" class="context-wrapp">
          <blob :blob="blob"></blob>
          <ul class="context-menu" :id="contextMenuId(index)" tabindex="-1" v-if="isVisible(index)"
              :style="{top:top, left:left}">
            <li><a href="#" @click.prevent="openBlob({blob})">Select</a></li>
            <li>Second list item</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import * as getters from '../store/getters'
  import * as actions from '../store/actions'
  import blob from './Blob.vue'
  import settings from './../settings'
  import Vue from 'vue'
  import { mapGetters, mapActions } from 'vuex'

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
        viewMenu: {},
        top: '0px',
        left: '0px'
      }
    },

    methods: {
      ...mapActions([
        actions.openBlob
      ]),

      isVisible (index) {
        return !!this.viewMenu[index]
      },

      contextMenuId (index) {
        return `context-menu-${index}`
      },

      /**
       * @param {Number} top
       * @param {Number} left
       * @param el
       * @param {Number} index
       * @param {String} menuId
       */
      setMenu (top, left, el, index, menuId) {
        let largestHeight = window.innerHeight - el.offsetHeight - 25
        let largestWidth = window.innerWidth - el.offsetWidth - 25

        if (top > largestHeight) top = largestHeight
        if (left > largestWidth) left = largestWidth

        this.top = `${top}px`
        this.left = `${left}px`

        let docClickListener = ({target}) => {
          let targetEq = [target.id === menuId]
          while ((target = target.parentElement)) {
            targetEq.push(target.id === menuId)
          }

          if (targetEq.filter(eq => eq).length === 0) {
            this.closeMenu(index)
            document.removeEventListener('click', docClickListener)
          }
        }

        document.addEventListener('click', docClickListener)
      },

      /**
       * Close menu
       */
      closeMenu (index) {
        Vue.set(this.viewMenu, index, false)
      },

      /**
       * @param {MouseEvent} e
       * @param {Number} index
       */
      openMenu (e, index) {
        Vue.set(this.viewMenu, index, true)
        let menuId = this.contextMenuId(index)

        // interact with element only when it is already in dom
        Vue.nextTick(() => {
          let el = document.getElementById(menuId)
          el.focus()
          this.setMenu(e.y, e.x, el, index, menuId)
        })
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
