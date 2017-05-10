<template>
  <div id="tree">
    <tree-item :item="tree"></tree-item>
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

    .clearfix {
      clear: both;
    }

    ul {
      list-style-type: none;
      padding-left: 13px;
      margin: 0;
    }

    .el-button+.el-button {
      margin-left: 0;
    }
  }
</style>
