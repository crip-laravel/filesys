<template>
  <span class="action-btn" :class="'action-btn-' + size"><a @click="onClick">{{title}}</a></span>
</template>

<script>
  export default {
    name: 'action-btn',

    props: {
      title: {type: String},
      onClick: {type: Function},
      size: {type: String, default: _ => 'md'}
    },

    mounted () {
      console.log(`${this._name} mounted`, {title: this.title, size: this.size})
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
      cursor: pointer;
      display: block;
      height: 100%;
      padding: 10px;
      width: 100%;

      &:hover {
        background-color: darken($footer-text-color, 20%);
        border-color: $second-color;
        color: $link-color;
        text-decoration: none;
      }
    }
  }

</style>
