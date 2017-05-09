<template>
  <div>
    <actions-bar></actions-bar>
    <breadcrumb-bar></breadcrumb-bar>
    <el-row>
      <el-col :sm="8" :md="6">
        <tree></tree>
      </el-col>
    </el-row>
    <!--<div class="row">
      <tree class="col-sm-4 col-md-3"></tree>
      <content-container class="col-sm-8 col-md-9 position-inherit">
        <uploads></uploads>
        <blobs></blobs>
      </content-container>
    </div>-->
  </div>
</template>

<script>
  import * as actions from './store/actions'
  import actionsBar from './components/actions-bar/ActionsBar.vue'
  import breadcrumbBar from './components/breadcrumb-bar/BreadcrumbBar.vue'
  import tree from './components/body/tree/Tree.vue'
  /* import blobs from './components/body/content/Blobs.vue'
  import contentContainer from './components/body/content/ContentContainer.vue'
  import uploads from './components/body/uploads/Uploads.vue' */

  export default {
    name: 'app',

    components: {
      actionsBar,
      tree,
      breadcrumbBar /* ,
      contentContainer,
      blobs,
      uploads */
    },

    mounted () {
      this.$store.dispatch(actions.fetchContent)
      this.$store.dispatch(actions.fetchTree)
    }
  }
</script>
