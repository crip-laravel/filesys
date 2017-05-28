<template>
  <div class="action-btn" :class="btnClass">
    <a class="inte-item transition-all" @click="onBtnClick" :class="linkClass">
      <img class="action-icon hidden-xs" :src="iconUrl" v-if="hasIcon">
      <div class="action-text">
        <slot></slot>
      </div>
    </a>
  </div>
</template>

<script>
  import settings from '../../settings'

  export default {
    name: 'actions-bar-btn',

    props: {
      icon: {type: String, required: false, 'default': () => ''},
      size: {type: String, required: false, 'default': () => 'md'},
      active: {type: Boolean, required: false, 'default': () => false},
      disabled: {type: Boolean, required: false, 'default': () => false}
    },

    computed: {
      /**
       * Determines icon prop persistence.
       * @returns {Boolean}
       */
      hasIcon () {
        return !!this.icon
      },

      /**
       * Gets current icon absolute URL.
       * @returns {String}
       */
      iconUrl () {
        return settings.icon(this.icon)
      },

      /**
       * Gets current element class definition.
       * @returns {Array.<String>}
       */
      btnClass () {
        return [`action-btn-${this.size}`]
      },

      /**
       * Gets current element link class definition.
       * @returns {{active: Boolean, disabled: Boolean}}
       */
      linkClass () {
        return {
          'active': this.active,
          'disabled': this.disabled
        }
      }
    },

    methods: {
      /**
       * Emit click of inner element to parent.
       * @param {MouseEvent} e
       */
      onBtnClick (e) {
        this.$emit('click', e)
      }
    }
  }
</script>

<style lang="sass" type="text/scss" scoped>
  .action-btn {
    &.action-btn-sm a {
      height: auto;
      margin-bottom: 2px;

      .action-icon {
        display: inline-block;
        margin: 0 auto;
        max-height: 20px;
        max-width: 20px;
      }

      .action-text {
        display: inline-block;
      }
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
