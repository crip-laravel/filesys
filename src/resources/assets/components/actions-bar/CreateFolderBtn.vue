<template>
  <el-button size="large"
             :icon="btnIcon"
             :type="btnType"
             @click="showCreateFolderBlob">
    {{ content }}
  </el-button>
</template>

<script>
  import * as actions from './../../store/actions'
  import * as getters from './../../store/getters'
  import * as mutations from './../../store/mutations'

  export default {
    name: 'create-folder-btn',

    computed: {
      /**
       * Determines current visibility state of create folder blob.
       * @returns {Boolean} Returns <c>true</c> if create folder blob is visible
       * on the UI.
       */
      createFolderBlobIsVisible () {
        return this.$store.getters[getters.getCreateFolderBlobVisibility] &&
          !this.$store.getters[getters.getIsAnyBlobInSelectedMode]
      },

      /**
       * Current state button type value.
       * @returns {String} 'primary' if create folder is visible at this moment.
       */
      btnType () {
        return this.createFolderBlobIsVisible ? 'primary' : ''
      },

      /**
       * Current state button icon name.
       * @returns {String} 'circle-check' if create folder is visible and 'plus'
       * otherwise.
       */
      btnIcon () {
        return this.createFolderBlobIsVisible ? 'circle-check' : 'plus'
      },

      /**
       * Content of the button in current state.
       */
      content () {
        return this.createFolderBlobIsVisible
          ? 'Save new folder' : 'Create Folder'
      }
    },

    methods: {
      /**
       * Sets crate folder blob visibility state to true.
       */
      showCreateFolderBlob () {
        if (this.$store.getters[getters.getIsAnyBlobInSelectedMode]) {
          this.$store.commit(mutations.removeSelectedBlob)
        }

        if (!this.createFolderBlobIsVisible) {
          return this.$store.commit(mutations.setCreateFolderBlobVisibility, true)
        }

        this.$store.dispatch(actions.saveBlob, this.$store.getters[getters.getNewFolder])
      }
    }
  }
</script>
