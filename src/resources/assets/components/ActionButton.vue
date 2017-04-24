<template>
  <div class="action-btn" :class="elClasses">
    <a class="inte-item" @click="onClick" :class="linkClasses">
      <img class="action-icon hidden-xs" :src="iconUrl" v-if="hasIcon">
      <div class="action-text">{{title}}</div>
    </a>
  </div>
</template>

<script>
  import * as getters from '../store/getters'
  import settings from '../settings'
  import { mapGetters } from 'vuex'

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
      ...mapGetters([
        getters.isLoading
      ]),
      elClasses () { return [`action-btn-${this.size}`] },
      linkClasses () {
        return {
          'active': this.active,
          'disabled': this.isLoading || this.disabled
        }
      }
    },

    data () {
      return {
        iconUrl: settings.icon(this.icon),
        hasIcon: !!this.icon
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../sass/_variables";

  .action-btn {
    &.action-btn-sm a {
      height: auto;
      margin-bottom: 2px;
    }

    a {
      display: block;
      padding: 0 4px;
      margin: 4px;

      .action-icon {
        display: block;
        margin: 0 auto;
        max-height: 56px;
        max-width: 56px;
      }

      .action-text {
        text-align: center;
      }
    }
  }
</style>
