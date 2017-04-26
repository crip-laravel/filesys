<template>
  <btn size="lg" icon="rename"
       :active="isRenameBlobActive"
       :disabled="isRenameBlobDisabled"
       @click="enableBlobRename">
    Rename
  </btn>
</template>

<script>
  import btn from './Btn.vue'
  import * as g from '../../store/getters'
  import { setRename } from '../../store/mutations'

  export default {
    name: 'rename_actions-bar-btn',

    components: {btn},

    computed: {
      /**
       * Determines is any of blob state is in rename mode.
       * @returns {Boolean}
       */
      isRenameBlobActive () {
        return this.$store.getters[g.getIsAnyBlobInRenameMode]
      },

      /**
       * Determines is any of blob state is in selected mode and there is not
       * enabled create state.
       * @returns {Boolean}
       */
      isRenameBlobDisabled () {
        return !(this.isCreateFolderBlobHidden && this.isAnyBlobSelected)
      },

      /**
       * Determines current visibility state of create folder blob.
       * @returns {Boolean} Returns <c>false</c> if create folder blob is
       * visible on the UI.
       */
      isCreateFolderBlobHidden () {
        return !this.$store.getters[g.getCreateFolderBlobVisibility]
      },

      /**
       * Determines is any of blob state is in selected mode.
       * @returns {Boolean} Returns <c>true</c> if any of blob is in state of
       * selected.
       */
      isAnyBlobSelected () {
        return this.$store.getters[g.getIsAnyBlobInSelectedMode]
      }
    },

    methods: {
      /**
       * Enable rename state for selected blob.
       */
      enableBlobRename () {
        this.$store.commit(setRename)
      }
    }
  }
</script>
