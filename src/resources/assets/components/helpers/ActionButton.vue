<template>
  <span class="action-btn" :class="'action-btn-' + size">
    <a @click="onClick" :class="{enabled}">{{title}}</a>
  </span>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as getters from '../../store/getters'

  export default {
    name: 'action-btn',

    props: {
      title: {type: String},
      onClick: {type: Function},
      disabled: {type: Boolean, default: () => false},
      size: {type: String, default: () => 'md'}
    },

    mounted () {
      console.log(`${this._name} mounted`, {title: this.title, size: this.size})
    },

    computed: {
      ...mapGetters([getters.loading]),
      enabled () {
        return !this.loading && !this.disabled
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../../sass/variables";

  .action-btn {
    display: block;

    &.action-btn-lg {
      height: 100px;
    }

    a {
      border: 1px solid transparent;
      color: $main-color;
      display: block;
      height: 100%;
      padding: 10px;
      text-decoration: none;
      width: 100%;

      &.enabled:hover {
        background-color: darken($footer-text-color, 20%);
        border-color: $second-color;
        color: $link-color;
        cursor: pointer;
      }
    }
  }

</style>
