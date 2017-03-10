<template>
  <div :class="classes">
    <a @click="onClick" :class="{enabled}">{{title}}</a>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as getters from '../../store/getters'

  export default {
    name: 'action-btn',

    props: {
      title: {type: String},
      onClick: {type: Function},
      active: {type: Boolean, default: () => false},
      disabled: {type: Boolean, default: () => false},
      size: {type: String, default: () => 'md'}
    },

    mounted () {
      console.log(`${this._name} mounted`, {title: this.title, size: this.size})
    },

    computed: {
      ...mapGetters([getters.loading]),
      enabled () { return !this.loading && !this.disabled },
      classes () { return {'action-btn': true, [`action-btn-${this.size}`]: true, 'active': this.active} }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../../sass/variables";

  .action-btn {
    &.action-btn-lg {
      height: 100px;
    }

    &.action-btn-sm a {
      height: auto;
      padding: 0 10px;
      margin-bottom: 2px;
    }

    &.active a {
      background-color: darken($footer-text-color, 10%);
      border-color: $second-color;
      color: $link-color;
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
