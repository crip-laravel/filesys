<template>
  <div class="container-fluid">
    <actions-bar class="row"></actions-bar>
    <breadcrumb class="row"></breadcrumb>
    <div class="row">
      <tree class="col-sm-4 col-md-3"></tree>
      <content-section class="col-sm-8 col-md-9 position-inherit">
      </content-section>
    </div>
  </div>
</template>

<script>
  import actionsBar from './components/actions-bar/ActionsBar.vue'
  import breadcrumb from './components/breadcrumb-bar/Breadcrumb.vue'
  import tree from './components/body/tree/Tree.vue'
  import contentSection from './components/body/content/Content.vue'

  import { setPath } from './store/mutations'

  export default {
    name: 'app',

    mounted () {
      this.$store.commit(setPath, '')
    },

    components: {actionsBar, breadcrumb, tree, contentSection}
  }
</script>

<style>
  .position-inherit {
    position: inherit;
  }
</style>
