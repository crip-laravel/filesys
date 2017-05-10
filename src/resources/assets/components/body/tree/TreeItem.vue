<template>
  <div>
    <div class="clearfix">

      <el-button
              type="text"
              class="toggle-btn transformable"
              icon="caret-right"
              v-if="item.children.length"
              :class="{disabled: isLoading, expanded: this.isSubmenuOpen}"
              @click.prevent="toggleItem">
      </el-button>

      <el-button
              type="text"
              :class="classes"
              @click.prevent="changePath">
        {{ item.label }}
      </el-button>

    </div>
    <transition name="collapse">
      <ul v-if="item.children.length && isSubmenuOpen" class="tree-submenu">
        <li v-for="child in item.children">
          <tree-item :item="child"></tree-item>
        </li>
      </ul>
    </transition>
  </div>
</template>

<script>
  import TreeItem from '../../../models/TreeItem'
  import * as actions from '../../../store/actions'
  import * as getters from '../../../store/getters'

  export default {
    name: 'tree-item',

    props: {
      /**
       * Tree item instance.
       */
      item: {type: TreeItem, required: true}
    },

    computed: {
      /**
       * Gets page loading state.
       * @return {Boolean}
       */
      isLoading () {
        return this.$store.getters[getters.isLoading]
      },

      /**
       * Current state path.
       * @return {String}
       */
      path () {
        return this.$store.getters[getters.getPath]
      },

      /**
       * Classes of link at current state.
       * @return {String}
       */
      classes () {
        return {
          disabled: this.isLoading,
          offset: !this.item.children.length,
          active: this.item.path === this.path || this.isClosedAndChildActive
        }
      },

      /**
       * Determines is this item closed state and some of the children is active.
       * @return {Boolean}
       */
      isClosedAndChildActive () {
        if (!this.item.children.length || this.isSubmenuOpen) { return false }

        return this.isAnyActive(this.item.children)
      },

      /**
       * Determines is this item collapsed at this moment in a state.
       * @return {Boolean}
       */
      isSubmenuOpen () {
        return this.isOpen || this.item.isOpen
      }
    },

    data () {
      return {
        isOpen: false
      }
    },

    methods: {
      /**
       * Open folder content of current tree item.
       */
      changePath () {
        this.$store.dispatch(actions.changePath, this.item.path)
      },

      /**
       * Toggle current tree item open state.
       */
      toggleItem () {
        this.isOpen = this.item.isOpen = !this.isSubmenuOpen
      },

      /**
       * Determine is any of children in state of active.
       * @param {Array.<TreeItem>} children
       * @returns {Boolean}
       */
      isAnyActive (children) {
        let isActive = false

        children.forEach(item => {
          if (item.path === this.path) {
            isActive = true
          }

          if (!isActive && item.children.length > 0 && this.isAnyActive(item.children)) {
            isActive = true
          }
        })

        return isActive
      }
    }
  }
</script>

<style lang="sass" type="text/scss" scoped>
  .el-button {

    &.transformable {
      transform: rotate(0deg);
      transition: transform .3s ease-in-out;

      &.expanded {
        transform: rotate(90deg);
      }
    }

    &.toggle-btn {
      padding: 5px;
    }

    &.offset {
      margin-left: 28px;
    }

    &.active {
      color: #97a8be;
    }
  }

  .tree-submenu {
    &.collapse-enter-active, &.collapse-leave-active {
      overflow: hidden;
      transition: opacity .3s ease-in-out;
    }

    &.collapse-enter, &.collapse-leave-to {
      opacity: 0;
    }
  }
</style>
