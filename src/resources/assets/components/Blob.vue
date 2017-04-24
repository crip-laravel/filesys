<template>
  <div class="blob inte-item" :class="classes" @click="setSelectedBlob(blob)" :title="title">
    <div class="thumb" @dblclick="openBlob({blob})">
      <img :src="blob.thumb">
    </div>
    <div v-if="blob.$edit">
      <form @submit.prevent="saveBlob(blob)">
        <input name="name" :id="blob.$id" v-model="blob.newName">
      </form>
    </div>
    <div v-else class="blob-description" @dblclick="startEditBlob">
      {{title}}
    </div>
  </div>
</template>

<script>
  import * as actions from '../store/actions'
  import * as getters from '../store/getters'
  import * as mutations from '../store/mutations'
  import Blob from '../models/Blob'
  import { mapGetters, mapMutations, mapActions } from 'vuex'

  export default {
    name: 'blob',

    props: {
      blob: {type: Blob}
    },

    computed: {
      ...mapGetters([
        getters.selectedBlob,
        getters.isLoading
      ]),
      classes () { return {active: this.selectedBlob.$id === this.blob.$id, disabled: this.isLoading} },
      title () { return this.blob.isDir ? this.blob.name : this.blob.full_name }
    },

    methods: {
      ...mapMutations([
        mutations.setSelectedBlob
      ]),

      ...mapActions([
        actions.openBlob,
        actions.saveBlob,
        actions.startEditBlob
      ])
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../sass/_variables";

  .blob {
    input {
      border-color: transparent;
      outline: none;
      padding: 0 5px;
      width: 100%;
    }

    .blob-description {
      height: 28px;
      margin: 0 4px;
      text-align: center;
    }
  }

  .list .blob {
    .blob-description {
      margin: 5px 0 0 64px;
      text-align: left;
    }

    form {
      margin: 3px 0 0 10px;
      min-width: 218px;
      width: 50%;
      float: left;
    }
  }
</style>
