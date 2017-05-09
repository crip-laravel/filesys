<template>
  <div>
    <actions-bar></actions-bar>
    <breadcrumb-bar></breadcrumb-bar>
    <el-row>
      <el-col :sm="8" :md="6">
        <el-tree
                accordion
                :expand-on-click-node="false"
                :data="tree"
                @node-click="handleNodeClick"></el-tree>
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
  import * as getters from './store/getters'
  import * as actions from './store/actions'
  import actionsBar from './components/actions-bar/ActionsBar.vue'
  import breadcrumbBar from './components/breadcrumb-bar/BreadcrumbBar.vue'
  /* import blobs from './components/body/content/Blobs.vue'
  import contentContainer from './components/body/content/ContentContainer.vue'
  import tree from './components/body/tree/Tree.vue'
  import uploads from './components/body/uploads/Uploads.vue' */

  export default {
    name: 'app',

    components: {
      actionsBar,
      breadcrumbBar /* ,
      tree,
      contentContainer,
      blobs,
      uploads */
    },

    mounted () {
      this.$store.dispatch(actions.fetchContent)
      this.$store.dispatch(actions.fetchTree)
    },

    computed: {
      tree () {
        return this.$store.getters[getters.getTree]
      },

      /**
       * Current state path.
       * @return {String}
       */
      path () {
        return this.$store.getters[getters.getPath]
      }
    },

    methods: {
      /**
       * Open folder content of selected tree item.
       */
      handleNodeClick (data) {
        if (data.path !== this.path) {
          this.$store.dispatch(actions.changePath, data.path)
        }
      }
    }
  }
</script>
