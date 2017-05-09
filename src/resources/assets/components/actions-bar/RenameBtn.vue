<template>
  <el-button
          size="large"
          :icon="btnIcon"
          :type="btnType"
          :disabled="isRenameBlobDisabled"
          @click="enableBlobRename">
    {{ content }}
  </el-button>
</template>

<script>
  import * as actions from '../../store/actions'
  import * as getters from '../../store/getters'
  import * as mutations from '../../store/mutations'

  export default {
    name: 'rename_actions-bar-btn',

    computed: {
      /**
       * Determines is any of blob state is in rename mode.
       * @returns {Boolean}
       */
      isRenameBlobActive () {
        return this.$store.getters[getters.getIsAnyBlobInRenameMode]
      },

      /**
       * Determines is any of blob state is in selected mode and there is not
       * enabled create state.
       * @returns {Boolean}
       */
      isRenameBlobDisabled () {
        return !(this.isCreateFolderBlobHidden && this.isAnyBlobSelected) ||
          this.isRenameBlobActive && this.blob.name === this.blob.$newName
      },

      /**
       * Determines current visibility state of create folder blob.
       * @returns {Boolean} Returns <c>false</c> if create folder blob is
       * visible on the UI.
       */
      isCreateFolderBlobHidden () {
        return !this.$store.getters[getters.getCreateFolderBlobVisibility]
      },

      /**
       * Determines is any of blob state is in selected mode.
       * @returns {Boolean} Returns <c>true</c> if any of blob is in state of
       * selected.
       */
      isAnyBlobSelected () {
        return this.$store.getters[getters.getIsAnyBlobInSelectedMode]
      },

      /**
       * Current state button type value.
       * @returns {String} 'primary' if create folder is visible at this moment.
       */
      btnType () {
        return this.isRenameBlobActive ? 'primary' : ''
      },

      /**
       * Current state button icon name.
       * @returns {String} 'circle-check' if create folder is visible and 'edit'
       * otherwise.
       */
      btnIcon () {
        return this.isRenameBlobActive ? 'circle-check' : 'edit'
      },

      /**
       * Get button content depending on the rename state.
       */
      content () {
        return this.isRenameBlobActive ? 'Save' : 'Rename'
      },

      /**
       * Gets selected blob instance.
       */
      blob () {
        return this.$store.getters[getters.getSelectedBlob]
      }
    },

    methods: {
      /**
       * Enable rename state for selected blob in vuex store.
       */
      enableBlobRename () {
        if (this.isRenameBlobActive) {
          return this.$store.dispatch(actions.saveBlob, this.blob)
        }

        return this.isRenameBlobDisabled || this.$store.commit(mutations.setRename)
      }
    }
  }
</script>
