<template>
  <div class="content-container" :class="[displayType]">
    <slot></slot>
  </div>
</template>

<script>
  import * as getters from '../../../store/getters'

  export default {
    name: 'content-container',

    computed: {
      /**
       * Get current display type of content.
       * @return {String}
       */
      displayType () {
        return this.$store.getters[getters.getDisplayType]
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

  .content-container {
    border-top: 1px solid $menu-border-color;
  }
</style>
