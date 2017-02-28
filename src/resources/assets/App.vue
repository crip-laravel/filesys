<template>
  <div class="container-fluid">
    <actions class="row"></actions>
    <breadcrumb class="row"></breadcrumb>
    <div class="row">
      <tree class="col-md-4"></tree>
      <files class="col-md-8"></files>
    </div>
  </div>
</template>

<script>
  import actions from './components/Actions.vue'
  import breadcrumb from './components/Breadcrumb.vue'
  import tree from './components/Tree.vue'
  import files from './components/Files.vue'

  export default {
    mounted () {
      console.log('app mounted')
    },

    components: {actions, breadcrumb, tree, files}
  }
</script>
