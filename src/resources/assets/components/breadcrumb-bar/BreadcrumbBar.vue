<template>
  <el-row id="breadcrumb">
    <el-col :xs="4" :md="2">
      <el-button
              type="primary"
              icon="arrow-up"
              :disabled="isUpDisabled"
              @click.prevent="changePath(up)"></el-button>
    </el-col>

    <el-col :xs="16" :md="20">
      <breadcrumb></breadcrumb>
    </el-col>

    <el-col :xs="4" :md="2" class="text-right">
      <el-button
              type="primary"
              icon="loading"
              @click.prevent="refreshContent"></el-button>
    </el-col>
  </el-row>
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
  .text-right {
    text-align: right;
  }
</style>
