<template>
  <ol class="breadcrumb">
    <li v-for="item in items"
        :class="{active: item.isActive()}">
      <a v-if="!item.isActive()"
         href
         @click.prevent="changePath(item.path)">
        {{ item.text }}
      </a>
      <span v-else="">{{ item.text }}</span>
    </li>
  </ol>
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
          path: '',
          isActive: () => fullPath === ''
        }]

        parts.forEach((part, index) => {
          let path = parts.slice(0, index + 1).join('/')
          part !== '' && result.push({
            text: part,
            path: path,
            isActive: () => path === fullPath
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
  .breadcrumb {
    margin-bottom: 0;
    background-color: transparent;
  }
</style>
