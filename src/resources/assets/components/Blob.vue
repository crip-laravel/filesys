<template>
  <div class="blob" :class="classes" @click="selectItem(blob)">
    <div class="thumb thumbnail" @dblclick="openItem(blob)">
      <img :src="blob.thumb">
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

    .thumb {
      height: 105px;
      overflow: hidden;
      margin-bottom: 8px;

      img {
        margin-bottom: 6px;
        max-height: 100px;
      }
    }

    input {
      color: $brand-primary;
      width: 100%;
      padding: 0 5px;
      outline: none;
    }

    .blob-description {
      height: 28px;
      margin: 0 4px;
      text-align: center;
    }
  }

  .list .blob {
    border-bottom: 1px solid $second-color;

    .thumb {
      float: left;
      height: auto;
      margin: 4px 0 4px 4px;
      padding: 0;
      text-align: center;
      width: 50px;
      border: none;

      img {
        height: 25px;
        margin: 0 auto;
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
