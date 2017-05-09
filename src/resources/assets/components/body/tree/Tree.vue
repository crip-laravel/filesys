<template>
  <div id="tree">
    <ul class="root-ul">
      <li v-for="item in tree">
        <tree-item :item="item"></tree-item>
      </li>
    </ul>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'
  import TreeItem from '../../../models/TreeItem'
  import treeItem from './TreeItem.vue'

  export default {
    name: 'folder-tree',

    computed: {
      /**
       * Tree items collection from vuex store.
       * @return {Array}
       */
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
  #tree {
    overflow-x: auto;
    padding-top: 6px;

    .clearfix {
      clear: both;
    }

    .root a {
      padding-left: 5px;
    }

    ul.root-ul {
      margin: 0;
    }

    ul {
      border-left: 1px dotted gray;
      list-style-type: none;
      padding-left: 13px;
      margin-left: 13px;
    }

    > ul {
      border-left: none;
      padding: 0;
    }
  }
</style>
