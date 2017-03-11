<template>
  <div id="breadcrumb">
    <ol class="breadcrumb">
      <li v-for="item in items" :class="{active: item.isActive()}">
        <a v-if="!item.isActive()" href="#breadcrumb" @click="changePath(item.path)">{{item.text}}</a>
        <span v-else="">{{item.text}}</span>
      </li>
    </ol>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex'
  import * as getters from '../store/getters'
  import * as actions from '../store/actions'

  export default {
    name: 'breadcrumb',

    mounted () {
      console.log(this.breadcrumb, this.path, this.items)
    },

    computed: {
      ...mapGetters([
        getters.path,
        getters.breadcrumb
      ]),
      items () {
        let result = []
        let fullPath = this.path

        this.breadcrumb.forEach((text, index) => {
          let path = this.breadcrumb.slice(0, index + 1).join('/')
          result.push({
            text, path, isActive () { return path === fullPath }
          })
        })

        return result
      }
    },

    methods: {
      ...mapActions([
        actions.changePath
      ])
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  #breadcrumb {
    min-height: 38px;

    .breadcrumb {
      margin-bottom: 0;
    }
  }
</style>
