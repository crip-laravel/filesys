<template>
  <div class="blob" :class="classes" @click="select(blob)">
    <div class="thumb">
      <img class="thumbnail" :src="blob.thumb">
    </div>
    <div v-if="blob.$edit">
      <form @submit.prevent="save">
        <input name="name" v-model="blob.newName">
      </form>
    </div>
    <div v-else class="blob-description" @dbclick="enableEdit()">{{blob.full_name}}</div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import Blob from '../../models/Blob'
  import * as getters from '../../store/getters'
  import * as mutations from '../../store/mutations'

  export default {
    name: 'blob',

    props: {
      blob: {type: Blob}
    },

    computed: {
      ...mapGetters([getters.selectedBlob]),
      classes () { return {active: this.selectedBlob === this.blob} }
    },

    methods: {
      select (blob) {
        this.$store.commit(mutations.selectItem, {item: blob})
      },

      save () {
        this.blob.$edit = false
        console.log('save-blob', this.blob)
      },

      enableEdit () {
        this.blob.$edit = true
        console.log(this.$el)
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../../sass/variables";

  .blob {
    overflow: hidden;

    &.active {
      background-color: darken($footer-text-color, 10%);
      border-color: $second-color;
      color: $link-color;
    }

    &:hover {
      background-color: darken($footer-text-color, 20%);
      border-color: $second-color;
      color: $link-color;
      cursor: pointer;
    }

    .thumb img {
      width: 100%;
      height: 100px;
      margin-bottom: 6px;
    }

    input {
      width: 100%;
      color: $brand-primary;
    }

    .blob-description {
      height: 28px;
    }
  }

  .list .blob {
    border-bottom: 1px solid $second-color;

    .thumb {
      width: 50px;
      float: left;

      img {
        height: 25px;
        margin: 4px;
      }
    }

    .blob-description {
      margin: 5px 0 0 10px;
      float: left;
    }
  }
</style>
