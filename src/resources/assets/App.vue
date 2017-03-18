<template>
  <div class="container-fluid">
    <actions class="row"></actions>
    <breadcrumb class="row"></breadcrumb>
    <div class="row">
      <tree class="col-sm-4 col-md-3"></tree>
      <blobs class="col-sm-8 col-md-9 display-default"></blobs>
    </div>
  </div>
</template>

<script>
  import actions from './components/Actions.vue'
  import breadcrumb from './components/Breadcrumb.vue'
  import tree from './components/Tree.vue'
  import blobs from './components/Blobs.vue'

  import { fetchContent } from './store/actions'

  export default {
    name: 'app',

    mounted () {
      this.$store.dispatch(fetchContent)
    },

    components: {actions, breadcrumb, tree, blobs}
  }
</script>

<style>
  .display-default {
    position: inherit;
  }
</style>
