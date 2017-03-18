<template>
  <div id="tree">
    <item :item="root"></item>
    <ul>
      <li v-for="item in treeFolders">
        <item :item="item"></item>
      </li>
    </ul>
  </div>
</template>

<script>
  import * as actions from '../store/actions'
  import * as getters from '../store/getters'
  import item from './TreeItem.vue'
  import TreeItem from '../models/TreeItem'
  import { mapGetters, mapActions } from 'vuex'

  export default {
    mounted () {
      this[actions.fetchTree]()
    },

    computed: {
      ...mapGetters([
        getters.treeFolders
      ])
    },

    data () {
      return {
        root: new TreeItem({name: 'Home', path: ''})
      }
    },

    methods: {
      ...mapActions([
        actions.fetchTree
      ])
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
