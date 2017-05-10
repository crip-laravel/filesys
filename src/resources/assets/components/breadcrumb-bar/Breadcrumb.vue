<template>
  <el-breadcrumb>
    <el-breadcrumb-item
            v-for="item in items"
            :key="item.path">
      <el-button
              type="text"
              @click.prevent="changePath(item.path)">
        {{ item.text }}
      </el-button>
    </el-breadcrumb-item>
  </el-breadcrumb>
</template>

<script>
  import * as actions from '../../store/actions'
  import * as getters from '../../store/getters'

  export default {
    name: 'breadcrumb',

    computed: {
      /**
       * Calculate breadcrumb items.
       * @returns {Array}
       */
      items () {
        let fullPath = this.$store.getters[getters.getPath]
        let parts = fullPath.split('/')

        // Home button at the beginning of the breadcrumb.
        let result = [{
          text: 'Home',
          path: ''
        }]

        parts.forEach((part, index) => {
          let path = parts.slice(0, index + 1).join('/')
          part !== '' && result.push({
            text: part,
            path: path
          })
        })

        return result
      }
    },

    methods: {
      /**
       * Change current location.
       * @param path
       */
      changePath (path) {
        this.$store.dispatch(actions.changePath, path)
      }
    }
  }
</script>

<style>
  .el-breadcrumb__item__inner .el-button {
    color: inherit;
    cursor: inherit;
  }
</style>
