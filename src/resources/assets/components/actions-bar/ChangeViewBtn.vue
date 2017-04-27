<template>
  <btn size="sm"
       :active="isViewActive"
       @click="setView">
    <slot></slot>
  </btn>
</template>

<script>
  import * as g from '../../store/getters'
  import btn from './Btn.vue'
  import { setDisplayType } from '../../store/mutations'

  export default {
    name: 'change-view_actions-bar-btn',

    components: {btn},

    props: {
      /**
       * Display type identifier.
       */
      view: {type: String, required: true}
    },

    computed: {
      /**
       * Determines is vuex store display type same as current button view type.
       * @returns {Boolean}
       */
      isViewActive () {
        return this.$store.getters[g.getDisplayType] === this.view
      }
    },

    methods: {
      /**
       * Set current view as active one in vuex store.
       * @returns {Boolean}
       */
      setView () {
        return !this.isViewActive && this.$store.commit(setDisplayType, this.view)
      }
    }
  }
</script>
