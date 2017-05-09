<template>
  <el-button
          size="large"
          icon="delete"
          :disabled="isDeleteBlobDisabled"
          @click="deleteBlob">
    Delete
  </el-button>
</template>

<script>
  import * as actions from '../../store/actions'
  import * as getters from '../../store/getters'

  export default {
    name: 'delete-btn',

    computed: {
      /**
       * Determines is any of blob state is in selected mode and there is not
       * enabled create state.
       * @returns {Boolean}
       */
      isDeleteBlobDisabled () {
        return !(this.isCreateFolderBlobHidden && this.isAnyBlobSelected)
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
       * Gets selected blob instance.
       */
      blob () {
        return this.$store.getters[getters.getSelectedBlob]
      }
    },

    methods: {
      /**
       * Call delete blob action in vuex store.
       */
      deleteBlob () {
        return this.isDeleteBlobDisabled ||
          this.$store.dispatch(actions.deleteBlob, this.blob)
      }
    }
  }
</script>
