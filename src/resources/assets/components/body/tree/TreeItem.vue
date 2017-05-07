<template>
  <div>
    <div class="clearfix">

      <a href
         class="toggle inte-item"
         v-if="item.children.length"
         :class="{disabled: isLoading}"
         @click.prevent="toggle">{{ stateSign }}</a>

      <a href
         class="tree-link inte-item"
         :class="{disabled: isLoading, offset: !item.children.length}"
         @click.prevent="changePath">{{ item.name }}</a>

    </div>

    <ul v-if="item.children.length && isOpen">
      <li v-for="child in item.children">
        <tree-item :item="child"></tree-item>
      </li>
    </ul>
  </div>
</template>

<script>
  import TreeItem from '../../../models/TreeItem'
  import * as actions from '../../../store/actions'
  import * as getters from '../../../store/getters'

  export default {
    name: 'tree-item',

    props: {
      /**
       * Tree item instance.
       */
      item: {type: TreeItem, required: true}
    },

    computed: {
      /**
       * Gets page loading state.
       * @return {Boolean}
       */
      isLoading () {
        return this.$store.getters[getters.isLoading]
      },

      /**
       * State sign indicating to open or close current item tree.
       * @return {string}
       */
      stateSign () {
        return this.isOpen ? '-' : '+'
      }
    },

    data () {
      return {
        isOpen: false
      }
    },

    methods: {
      /**
       * Open folder content of current tree item.
       */
      changePath () {
        this.$store.dispatch(actions.changePath, this.item.path)
      },

      /**
       * Toggle current tree item open state.
       */
      toggle () {
        this.isOpen = !this.isOpen
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  .tree-link {
    display: block;
  }

  .toggle {
    float: left;
    padding: 2px 10px;
  }

  .offset {
    padding-left: 28px;
  }
</style>
