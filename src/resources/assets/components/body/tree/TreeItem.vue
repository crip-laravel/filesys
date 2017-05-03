<template>
  <div>
    <div class="clearfix">

      <a href
         class="toggle inte-item"
         v-if="item.children.length"
         :class="{disabled: isLoading}"
         @click="toggle">{{ sign }}</a>

      <a href
         class="tree-link inte-item"
         :class="{disabled: isLoading}"
         @click="changePath(item.path)">{{ item.name }}</a>

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
  import { changePath } from '../../../store/actions'
  import { isLoading } from '../../../store/getters'
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

<style lang="sass" type="text/scss">
  .tree-link {
    display: block;
  }

  .toggle {
    float: left;
    padding: 2px 10px;
  }
</style>
