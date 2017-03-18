<template>
  <div>
    <div class="clearfix">
      <a href="#" @click="toggle()" v-if="item.children.length" class="toggle">{{sign}}</a>
      <a href="#" @click="changePath(item.path)" class="tree-link" :class="{disabled: isLoading}">{{item.name}}</a>
    </div>

    <ul v-if="item.children.length && isOpen">
      <li v-for="child in item.children">
        <tree-item :item="child"></tree-item>
      </li>
    </ul>
  </div>
</template>

<script>
  import TreeItem from '../models/TreeItem'
  import { changePath } from '../store/actions'
  import { isLoading } from '../store/getters'
  import { mapActions, mapGetters } from 'vuex'

  export default {
    name: 'tree-item',

    props: {
      item: {type: TreeItem, required: true}
    },

    data () {
      return {
        isOpen: false
      }
    },

    computed: {
      ...mapGetters([
        isLoading
      ]),

      sign () { return this.isOpen ? '+' : '-' }
    },

    methods: {
      ...mapActions([
        changePath
      ]),

      toggle () {
        this.isOpen = !this.isOpen
      }
    }
  }
</script>

<style lang="sass" rel="stylesheet/scss">
  .tree-link {
    display: block;

    .disabled {
      opacity: 0.5;
    }
  }

  .toggle {
    float: left;
    padding: 0 7px;
  }
</style>
