<template>
  <btn size="lg" icon="add-folder"
       @click="showCreateFolderBlob"
       :active="createFolderBlobIsVisible">
    {{ content }}
  </btn>
</template>

<script>
  import * as actions from './../../store/actions'
  import * as getters from './../../store/getters'
  import * as mutations from './../../store/mutations'
  import btn from './Btn.vue'

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
        return this.$store.getters[getters.getCreateFolderBlobVisibility] &&
          !this.$store.getters[getters.getIsAnyBlobInSelectedMode]
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
          this.$store.commit(mutations.setCreateFolderBlobVisibility, false)
        }

        if (!this.createFolderBlobIsVisible) {
          return this.$store.commit(mutations.setCreateFolderBlobVisibility, true)
        }

        this.$store.dispatch(actions.saveBlob, this.$store.getters[getters.getNewFolder])
      }
    }
  }
</script>
