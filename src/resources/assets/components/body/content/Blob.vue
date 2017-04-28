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
        <input name="name" :id="blob.$id" v-model="blob.$newName">
      </form>
    </div>
    <div v-else="" class="blob-description" @dblclick="setBlobRename">
      {{ blob.fullName }}
    </div>
  </div>
</template>

<script>
  import * as a from '../../../store/actions'
  import * as g from '../../../store/getters'
  import * as m from '../../../store/mutations'
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
          'disabled': this.$store.getters[g.isLoading]
        }
      }
    },

    methods: {
      /**
       * Sets selected blob state for current blob.
       */
      selectBlob () {
        this.$store.commit(m.setSelectedBlob, this.blob.$id)
      },

      /**
       * Sets rename blob state for current blob.
       */
      setBlobRename () {
        this.$store.commit(m.setRenameBlob, this.blob.$id)
      },

      /**
       * Selects current blob for external use (editor or listener).
       */
      openBlob () {
        this.$store.dispatch(a.openBlob, this.blob)
      },

      /**
       * Send new name of blob to the server and set response as current blob
       * actual data in vuex store.
       */
      renameBlob () {
        this.$store.dispatch(
          m.renameBlob,
          {
            id: this.blob.$id,
            name: this.blob.$newName
          })
      }
    }
  }
</script>
