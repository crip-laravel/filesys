import Vue from 'vue'
import { creating } from '../getters'
import { deleteBlob, saveBlob, fetchTree } from '../actions'
import {
  setUpdatedBlob, setBlobEditMode, setCreateEnabled,
  removeBlob, removeSelectedBlob
} from '../mutations'

const state = {
  creating: false
}

const actions = {
  /**
   * Delete blob on server side
   * @param commit
   * @param getters
   * @param dispatch
   */
  [deleteBlob]: ({commit, getters, dispatch}) => {
    let selected = getters.selectedBlob
    selected.delete().then(() => {
      commit(removeBlob, selected)

      if (selected.isDir) {
        dispatch(fetchTree)
      }
    })
  },

  /**
   * Save blob on server side.
   * @param commit
   * @param dispatch
   * @param {Blob} blob
   */
  [saveBlob]: ({commit, dispatch}, blob) => {
    blob.save()
      .then(newBlob => {
        commit(setUpdatedBlob, {
          id: blob.$id,
          blob: newBlob
        })

        commit(removeSelectedBlob)

        if (blob.isDir) {
          dispatch(fetchTree)
        }
      })
  }
}

const mutations = {
  /**
   * Update blob details.
   * @param state
   * @param {String} id
   * @param {Blob} blob
   */
  [setUpdatedBlob]: (state, {id, blob}) => {
    let toUpdate = state.blobs.filter(b => b.$id === id)[0]
    state.blobs.splice(state.items.indexOf(toUpdate), 1)
    state.blobs.push(blob)
  },

  /**
   * Mutate selected blob state to edit state.
   * @param state
   */
  [setBlobEditMode]: (state) => {
    if (state.selected && state.selected.name !== '..') {
      state.selected.$edit = !state.selected.$edit
      if (state.selected.$edit) {
        Vue.nextTick(() => document.getElementById(state.selected.$id).focus())
      }
    }
  },

  /**
   * Mutate create state as enabled.
   * @param state
   */
  [setCreateEnabled]: (state) => {
    state.creating = true
  }
}

const getters = {
  [creating]: (store) => store.creating
}

export default {state, actions, mutations, getters}
