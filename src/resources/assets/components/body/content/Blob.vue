<template>
  <div class="blob inte-item"
       :class="classes"
       :title="title"
       @click.prevent="selectBlob">
    <div class="thumb" @dblclick.prevent="openBlob">
      <img :src="blob.thumb">
    </div>

    <div v-if="blob.$rename">
      <form @submit.prevent="saveBlob">
        <input name="name"
               :id="'blob-input-' + blob.$id"
               @focus="$event.target.select()"
               v-focus="true"
               v-model="blob.$newName">
      </form>
    </div>
    <div v-else="" class="blob-description" @dblclick.prevent="enableRename">
      {{ blob.fullName }}
    </div>
  </div>
</template>

<script>
  import * as actions from '../../../store/actions'
  import * as getters from '../../../store/getters'
  import * as mutations from '../../../store/mutations'
  import Blob from '../../../models/Blob'
  import { focus } from 'vue-focus'

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
          'disabled': this.$store.getters[getters.isLoading],
          'has-error': this.errorMessage !== ''
        }
      },

      /**
       * Gets title for current blob.
       */
      title () {
        return this.errorMessage || this.blob.fullName
      }
    },

    data () {
      return {
        errorMessage: ''
      }
    },

    methods: {
      /**
       * Sets selected blob state for current blob only in case if it is not
       * selected already.
       */
      selectBlob () {
        const selected = this.$store.getters[getters.getSelectedBlob]
        if (!selected || selected.$id !== this.blob.$id) {
          this.$store.commit(mutations.setSelectedBlob, this.blob.$id)
        }
      },

      /**
       * Sets rename blob state for current blob.
       */
      enableRename () {
        this.$store.commit(mutations.setRenameBlob, this.blob.$id)
      },

      /**
       * Selects current blob for external use (editor or listener).
       */
      openBlob () {
        this.$store.dispatch(actions.openBlob, {blob: this.blob})
      },

      /**
       * Send new name of blob to the server and set response as current blob
       * actual data in vuex store.
       */
      saveBlob () {
        // Reset any of error to empty as we now requesting new update of the
        // file.
        this.errorMessage = ''

        this.$store.dispatch(actions.saveBlob, this.blob)
          .catch(error => { this.errorMessage = error })
      }
    },

    directives: {focus}
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
      height: 24px;
      margin: 0 4px;
      overflow: hidden;
      text-align: center;
      text-overflow: ellipsis;
      white-space: nowrap;
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
