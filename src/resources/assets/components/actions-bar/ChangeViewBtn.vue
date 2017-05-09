<template>
  <el-select v-model="viewType" size="large">
    <el-option
            v-for="view in viewTypes"
            :key="view.value"
            :label="view.label"
            :value="view.value">
    </el-option>
  </el-select>
</template>

<script>
  import * as g from '../../store/getters'
  import { setDisplayType } from '../../store/mutations'

  export default {
    name: 'change-view-btn',

    data () {
      let viewType = this.$store.getters[g.getDisplayType]

      return {
        viewType,
        viewTypes: [
          {value: 'grid', label: 'Grid view'},
          {value: 'list', label: 'List view'}
        ]
      }
    },

    watch: {
      viewType (value) {
        this.$store.commit(setDisplayType, value)
      }
    }
  }
</script>
