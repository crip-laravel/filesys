<template>
  <div class="container-fluid full-height">
    <actions class="row"></actions>
    <breadcrumb class="row"></breadcrumb>
    <div class="row full-height">
      <tree class="col-sm-4 col-md-3 full-height"></tree>
      <blobs class="col-sm-8 col-md-9 full-height"></blobs>
    </div>
  </div>
</template>

<script>
  import actions from './components/Actions.vue'
  import breadcrumb from './components/Breadcrumb.vue'
  import tree from './components/Tree.vue'
  import blobs from './components/Blobs.vue'

  import { loadContent } from './store/actions'

  export default {
    name: 'app',

    mounted () {
      console.log('app mounted')
      this.$store.dispatch(loadContent)
    },

    components: {actions, breadcrumb, tree, blobs}
  }
</script>
