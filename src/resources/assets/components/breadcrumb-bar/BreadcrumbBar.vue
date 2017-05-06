<template>
  <div id="breadcrumb">
    <div class="col-xs-2 col-md-1" :class="{disabled: isUpDisabled}">
      <a class="icon-wrapp" href @click.prevent="changePath(up)">
        <img class="icon" :src="iconUp">
      </a>
    </div>

    <div class="col-xs-8 col-md-10">
      <breadcrumb></breadcrumb>
    </div>

    <div class="col-xs-2 col-md-1 text-right">
      <a class="icon-wrapp" href @click.prevent="refreshContent">
        <img class="icon" :src="iconRefresh">
      </a>
    </div>
  </div>
</template>

<script>
  import * as actions from '../../store/actions'
  import * as getters from '../../store/getters'
  import settings from '../../settings'
  import breadcrumb from './Breadcrumb.vue'

  export default {
    name: 'breadcrumb-bar',

    components: {breadcrumb},

    data () {
      return {
        iconUp: settings.icon('up'),
        iconRefresh: settings.icon('refresh')
      }
    },

    computed: {
      /**
       * Calculate up path.
       * @returns {string}
       */
      up () {
        return this.$store.getters[getters.getPathUp]
      },

      /**
       * Determine is the current path same as root.
       * @returns {boolean}
       */
      isUpDisabled () {
        return this.$store.getters[getters.getPath] === ''
      }
    },

    methods: {
      /**
       * Change current location.
       * @param path
       */
      changePath (path) {
        this.$store.dispatch(actions.changePath, path)
      },

      /**
       * Refresh content of the current location.
       */
      refreshContent () {
        this.$store.dispatch(actions.fetchContent)
        this.$store.dispatch(actions.fetchTree)
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  #breadcrumb {
    min-height: 38px;

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
