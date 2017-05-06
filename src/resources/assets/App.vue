<template>
  <div class="container-fluid">
    <actions-bar class="row"></actions-bar>
    <breadcrumb-bar class="row"></breadcrumb-bar>
    <div class="row">
      <tree class="col-sm-4 col-md-3"></tree>
      <blobs class="col-sm-8 col-md-9 position-inherit"></blobs>
    </div>
  </div>
</template>

<script>
  import actionsBar from './components/actions-bar/ActionsBar.vue'
  import breadcrumbBar from './components/breadcrumb-bar/BreadcrumbBar.vue'
  import tree from './components/body/tree/Tree.vue'
  import blobs from './components/body/Blobs.vue'

  import { fetchTree, fetchContent } from './store/actions'

  export default {
    name: 'app',

    components: {actionsBar, breadcrumbBar, tree, blobs},

    mounted () {
      this.$store.dispatch(fetchContent)
      this.$store.dispatch(fetchTree)
    }
  }
</script>

<style>
  .position-inherit {
    position: inherit;
  }
</style>
