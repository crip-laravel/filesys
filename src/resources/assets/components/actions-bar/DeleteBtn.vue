<template>
  <btn size="lg" icon="delete"
       :disabled="isDeleteBlobDisabled"
       @click="deleteBlob">
    Delete
  </btn>
</template>

<script>
  import * as g from '../../store/getters'
  import btn from './Btn.vue'
  import { deleteBlob } from '../../store/actions'

  export default {
    name: 'delete_actions-bar-btn',

    components: {btn},

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
       * Call delete blob action in vuex store.
       */
      deleteBlob () {
        return this.isDeleteBlobDisabled || this.$store.dispatch(deleteBlob)
      }
    }
  }
</script>
