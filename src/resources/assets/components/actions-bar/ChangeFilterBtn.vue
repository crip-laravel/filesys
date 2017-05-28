<template>
  <btn size="sm"
       :active="isFilterActive"
       :disabled="isFiltersDisabled"
       :icon="filter"
       @click="toggleFilter">
    <slot></slot>
  </btn>
</template>

<script>
  import btn from './Btn.vue'
  import settings from '../../settings'
  import * as getters from '../../store/getters'
  import * as mutations from '../../store/mutations'

  export default {
    name: 'change-filter_actions-bar-btn',

    components: {btn},

    props: {
      filter: {type: String, required: true}
    },

    computed: {
      /**
       * Get current display filter setting value.
       * @returns {string}
       */
      currentFilter () {
        return this.$store.getters[getters.getDisplayFilter]
      },

      /**
       * Determines is the current button filter set as active for blobs.
       * @returns {boolean}
       */
      isFilterActive () {
        return this.currentFilter === this.filter
      },

      /**
       * Determines is the media type predefined by the manager opener.
       * @returns {boolean}
       */
      isFiltersDisabled () {
        return settings.mediaType() !== settings.mediaTypes.file
      }
    },

    methods: {
      /**
       * Toggle current filter property value.
       * @return {*|void}
       */
      toggleFilter () {
        if (this.isFiltersDisabled) { return }
        if (this.isFilterActive) {
          // Reset back to all files
          return this.$store.commit(
            mutations.setDisplayFilter,
            settings.mediaTypes.file)
        }

        return this.$store.commit(
          mutations.setDisplayFilter,
          this.filter)
      }
    }
  }
</script>
