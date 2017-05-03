<template>
  <div class="blob inte-item"
       :class="classes"
       :title="blob.fullName"
       @click="selectBlob">
    <div class="thumb" @dblclick="openBlob">
      <img :src="blob.thumb">
    </div>

    <div v-if="blob.$rename">
      <form @submit.prevent="renameBlob">
        <input name="name" :id="'blob-' + blob.$id" v-model="blob.$newName">
      </form>
    </div>
    <div v-else="" class="blob-description" @dblclick="setBlobRename">
      {{ blob.fullName }}
    </div>
  </div>
</template>

<script>
  import * as actions from '../../../store/actions'
  import * as getters from '../../../store/getters'
  import * as mutations from '../../../store/mutations'
  import Blob from '../../../models/Blob'

  export default {
    name: 'blob',

    props: {
      /**
       * Current blob definition.
       */
      blob: {type: Blob, required: true}
    },

    computed: {
      /**
       * Computes classes state of current blob.
       * @returns {{active: Boolean, disabled: Boolean}}
       */
      classes () {
        return {
          'active': this.blob.$selected,
          'disabled': this.$store.getters[getters.isLoading]
        }
      }
    },

    methods: {
      /**
       * Sets selected blob state for current blob.
       */
      selectBlob () {
        this.$store.commit(mutations.setSelectedBlob, this.blob.$id)
      },

      /**
       * Sets rename blob state for current blob.
       */
      setBlobRename () {
        this.$store.commit(mutations.setRenameBlob, this.blob.$id)
      },

      /**
       * Selects current blob for external use (editor or listener).
       */
      openBlob () {
        this.$store.dispatch(actions.openBlob, this.blob)
      },

      /**
       * Send new name of blob to the server and set response as current blob
       * actual data in vuex store.
       */
      renameBlob () {
        this.$store.dispatch(
          actions.renameBlob,
          {
            id: this.blob.$id,
            name: this.blob.$newName
          })
      }
    }
  }
</script>

<style lang="sass" type="text/scss">
  @import "../../../sass/variables";

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
