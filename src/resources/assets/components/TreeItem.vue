<template>
  <div>
    <a href="#" @click="changePath(item.path)" class="tree-link" :class="{disabled: loading}">{{item.name}}</a>

    <ul v-if="item.children.length">
      <li v-for="child in item.children">
        <tree-item :item="child"></tree-item>
      </li>
    </ul>
  </div>
</template>

<script>
  import { mapActions, mapGetters } from 'vuex'
  import TreeItem from '../models/TreeItem'
  import { changePath } from '../store/actions'
  import { loading } from '../store/getters'

  export default {
    name: 'tree-item',

    props: {
      item: {type: TreeItem, required: true}
    },

    computed: {
      ...mapGetters([
        loading
      ])
    },

    methods: {
      ...mapActions([
        changePath
      ])
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
</style>
