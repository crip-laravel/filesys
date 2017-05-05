<template>
  <btn size="lg"
       icon="rename"
       :active="isRenameBlobActive"
       :disabled="isRenameBlobDisabled"
       @click="enableBlobRename">
    {{ content }}
  </btn>
</template>

<script>
  import * as actions from '../../store/actions'
  import * as getters from '../../store/getters'
  import * as mutations from '../../store/mutations'
  import btn from './Btn.vue'

  export default {
    name: 'rename_actions-bar-btn',

    components: {btn},

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
