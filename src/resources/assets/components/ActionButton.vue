<template>
  <div :class="classes">
    <a @click="onClick" :class="{enabled}">
      <img :src="iconUrl" v-if="hasIcon" class="action-icon">
      <div class="action-text">{{title}}</div>
    </a>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as getters from '../store/getters'
  import settings from '../settings'

  export default {
    name: 'action-btn',

    props: {
      title: {type: String, required: true},
      onClick: {type: Function, required: true},
      icon: {type: String},
      active: {type: Boolean, default: () => false},
      disabled: {type: Boolean, default: () => false},
      size: {type: String, default: () => 'md'}
    },

    computed: {
      ...mapGetters([getters.loading]),
      enabled () { return !this.loading && !this.disabled },
      classes () { return {'action-btn': true, [`action-btn-${this.size}`]: true, 'active': this.active} }
    },

    data () {
      return {
        iconUrl: settings.icon(this.icon),
        hasIcon: !!this.icon
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/_variables";

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
      color: lighten($main-color, 30%);
      display: block;
      height: 100%;
      padding: 10px;
      text-decoration: none;
      width: 100%;

      .action-icon {
        display: block;
        margin: 0 auto;
        max-height: 56px;
        max-width: 56px;
        opacity: 0.5;
      }

      .action-text {
        text-align: center;
      }

      &.enabled {
        color: $main-color;

        .action-icon {
          opacity: 1;
        }

        &:hover {
          background-color: darken($footer-text-color, 20%);
          border-color: $second-color;
          color: $link-color;
          cursor: pointer;
        }
      }
    }
  }
</style>
