<template>
  <div id="breadcrumb">
    <div class="col-xs-2 col-md-1" :class="{disabled: isUpDisabled}">
      <a class="icon-wrapp" href="#" @click.prevent="changePath(up)">
        <img class="icon" :src="iconUp">
      </a>
    </div>
    <div class="col-xs-8 col-md-10">
      <ol class="breadcrumb">
        <li v-for="item in items" :class="{active: item.isActive()}">
          <a v-if="!item.isActive()" href="#" @click="changePath(item.path)">{{item.text}}</a>
          <span v-else="">{{item.text}}</span>
        </li>
      </ol>
    </div>
    <div class="col-xs-2 col-md-1 text-right">
      <a class="icon-wrapp" href="#" @click="refreshContent"><img class="icon" :src="iconRefresh"></a>
    </div>
  </div>
</template>

<script>
  import * as actions from '../store/actions'
  import * as getters from '../store/getters'
  import settings from '../settings'
  import { mapGetters, mapActions } from 'vuex'

  export default {
    name: 'breadcrumb',

    data () {
      return {
        iconUp: settings.icon('up'),
        iconRefresh: settings.icon('refresh')
      }
    },

    computed: {
      ...mapGetters([
        getters.path,
        getters.breadcrumb
      ]),

      /**
       * Calculate breadcrumb items.
       * @returns {Array}
       */
      items () {
        let result = []
        let fullPath = this.path

        this.breadcrumb.forEach((text, index) => {
          let path = this.breadcrumb.slice(0, index + 1).join('/')
          result.push({
            text, path, isActive () { return path === fullPath }
          })
        })

        return result
      },

      /**
       * Calculate up path.
       * @returns {string}
       */
      up () {
        let parts = this.path.split('/')
        return parts.splice(0, parts.length - 1).join('/')
      },

      /**
       * Determine is the current path same as root.
       * @returns {boolean}
       */
      isUpDisabled () {
        return this.path === ''
      }
    },

    methods: {
      ...mapActions([
        actions.changePath,
        actions.refreshContent
      ])
    }
  }
</script>

<style lang="sass" type="text/scss">
  #breadcrumb {
    min-height: 38px;

    .breadcrumb {
      margin-bottom: 0;
      background-color: transparent;
    }

    .icon {
      max-height: 20px;
    }

    .disabled {
      opacity: 0.5;
    }

    .icon-wrapp {
      display: block;
      padding: 8px 15px;
    }
  }
</style>
