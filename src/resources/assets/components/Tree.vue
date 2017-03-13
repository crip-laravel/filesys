<template>
  <div id="tree">
    <item :item="root"></item>
    <ul>
      <li v-for="item in items">
        <item :item="item"></item>
      </li>
    </ul>
  </div>
</template>

<script>
  import item from './TreeItem.vue'
  import treeApi from '../api/tree'
  import TreeItem from '../models/TreeItem'
  import { mapGetters } from 'vuex'
  import { treeCounter } from '../store/getters'

  export default {
    mounted () {
      this.fetchTree()
    },

    computed: {
      ...mapGetters([
        treeCounter
      ])
    },

    data () {
      return {
        items: [],
        root: new TreeItem({name: 'Home', path: ''})
      }
    },

    methods: {
      fetchTree () {
        treeApi.getAll()
          .then(tree => {
            this.items = tree.items
          })
      }
    },

    watch: {
      [treeCounter] () {
        this.fetchTree()
      }
    },

    components: {item}
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/variables";

  #tree {
    border-top: 1px solid $second-color;
    overflow-x: auto;

    ul {
      border-left: 1px dotted gray;
      list-style-type: none;
      margin-left: 9px;
      padding-left: 19px;
    }

    > ul {
      border-left: none;
      padding: 0;
    }
  }
</style>
