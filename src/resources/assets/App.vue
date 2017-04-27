<template>
  <div class="container-fluid">
    <actions-bar class="row"></actions-bar>
    <breadcrumb class="row"></breadcrumb>
    <div class="row">
      <tree class="col-sm-4 col-md-3"></tree>
      <blobs class="col-sm-8 col-md-9 position-inherit"></blobs>
    </div>
  </div>
</template>

<script>
  import actionsBar from './components/actions-bar/ActionsBar.vue'
  import breadcrumb from './components/Breadcrumb.vue'
  import tree from './components/Tree.vue'
  import blobs from './components/Blobs.vue'

  import { fetchContent } from './store/actions'

  export default {
    name: 'app',

    mounted () {
      this.$store.dispatch(fetchContent)
    },

    components: {actionsBar, breadcrumb, tree, blobs}
  }
</script>

<style>
  .position-inherit {
    position: inherit;
  }
</style>
