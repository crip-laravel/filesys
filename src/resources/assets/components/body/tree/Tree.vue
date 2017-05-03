<template>
  <div id="tree">
    <tree-item :item="root"></tree-item>
    <ul>
      <li v-for="item in tree">
        <tree-item :item="item"></tree-item>
      </li>
    </ul>
  </div>
</template>

<script>
  import * as actions from '../../../store/actions'
  import * as getters from '../../../store/getters'
  import TreeItem from '../../../models/TreeItem'
  import treeItem from './TreeItem.vue'

  export default {
    mounted () {
      // When tree component is mounted, fetch all data from the server and
      // apply it to the current component content.
      this.$store.dispatch(actions.fetchTree)
    },

    computed: {
      tree () {
        return this.$store.getters[getters.getTree]
      }
    },

    data () {
      return {
        root: new TreeItem({name: 'Home', path: ''})
      }
    },

    components: {treeItem}
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

  #tree {
    border-top: 1px solid $menu-border-color;
    overflow-x: auto;
    padding-top: 6px;

    ul {
      border-left: 1px dotted gray;
      list-style-type: none;
      padding-left: 26px;
    }

    > ul {
      border-left: none;
      padding: 0;
    }
  }
</style>
