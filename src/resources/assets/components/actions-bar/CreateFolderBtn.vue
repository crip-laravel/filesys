<template>
  <btn size="lg" icon="add-folder"
       @click="showCreateFolderBlob"
       :active="createFolderBlobIsVisible">
    Create Folder
  </btn>
</template>

<script>
  import btn from './Btn.vue'
  import { getCreateFolderBlobVisibility } from './../../store/getters'
  import { setCreateFolderBlobVisibility } from './../../store/mutations'

  export default {
    name: 'create-folder_actions-bar-btn',

    components: {btn},

    computed: {
      /**
       * Determines current visibility state of create folder blob.
       * @returns {Boolean} Returns <c>true</c> if create folder blob is visible
       * on the UI.
       */
      createFolderBlobIsVisible () {
        return this.$store.getters[getCreateFolderBlobVisibility]
      }
    },

    methods: {
      /**
       * Sets crate folder blob visibility state to true.
       */
      showCreateFolderBlob () {
        if (!this.createFolderBlobIsVisible) {
          this.$store.commit(setCreateFolderBlobVisibility, true)
        }
      }
    }
  }
</script>
