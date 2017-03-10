<template>
  <div class="blob" :class="classes" @click="selectItem(blob)">
    <div class="thumb" @dblclick="openItem(blob)">
      <img class="thumbnail" :src="blob.thumb">
    </div>
    <div v-if="blob.$edit">
      <form @submit.prevent="save">
        <input name="name" :id="blob.$id" v-model="blob.newName">
      </form>
    </div>
    <div v-else class="blob-description" @dblclick="enableEdit()">
      {{blob.isDir ? blob.name : blob.full_name}}
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex'
  import Blob from '../models/Blob'
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'
  import { changePath } from '../store/actions'

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
      ...mapMutations([
        mutations.selectItem,
        mutations.enableEdit,
        mutations.updateBlob,
        mutations.deselect
      ]),

      save () {
        this.blob.update()
          .then(newBlob => {
            this.updateBlob({
              id: this.blob.$id,
              blob: newBlob
            })

            this.deselect()
          })
      },

      /**
       * Open folder or file
       * @param {Blob} blob
       */
      openItem (blob) {
        if (blob.isDir) {
          return this.$store.dispatch(changePath, blob.full_name)
        }
        // TODO: implement file select for ckeditor or litebox
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="sass">
  @import "../sass/_variables";

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
      margin: 0 4px;
      height: 28px;
    }
  }

  .list .blob {
    border-bottom: 1px solid $second-color;

    .thumb {
      float: left;
      width: 50px;

      img {
        height: 25px;
        margin: 4px;
      }
    }

    .blob-description, form {
      float: left;
      margin: 5px 0 0 10px;
    }

    form {
      margin-top: 3px;
      min-width: 218px;
      width: 50%;
    }
  }
</style>
