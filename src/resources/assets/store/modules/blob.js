import settings from '../../settings'
import Vue from 'vue'
import { creating } from '../getters'
import {
  deleteBlob, saveBlob, openBlob, fetchTree, changePath
} from '../actions'
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
  },

  /**
   * Select file or open dir.
   * @param dispatch
   * @param {Blob} blob
   * @param {String} size
   */
  [openBlob]: ({dispatch}, {blob, size}) => {
    console.log({blob, size})
    if (blob.isDir) {
      return dispatch(changePath, blob.full_name)
    }

    let action = 'selectForCallback'

    if (settings.target() === 'tinymce') {
      action = 'selectTinyMce'
    }

    return dispatch(action, blob.url)
  },

  /**
   * Select file for tinyMCE
   * @param state
   * @param {String} url
   */
  selectTinyMce (state, url) {
    if (!top.tinymce) {
      throw new Error('tinyMCE is selected as target, but `window.top` does not contain it!')
    }

    let wManager = top.tinymce.activeEditor.windowManager

    if (top.tinymce.majorVersion < 4) {
      wManager.params.setUrl(url)
      wManager.close(wManager.params.mce_window_id)
    } else {
      wManager.getParams().setUrl(url)
      wManager.close()
    }
  },

  /**
   * Select url for user callback
   * @param state
   * @param {String} url
   */
  selectCallback (state, url) {
    let userCallback = settings.callback()
    let callback = _ => _

    if (userCallback) {
      callback = window[userCallback] || parent[userCallback] || top[userCallback]
    } else {
      callback = window.cripFilesystemManager || parent.cripFilesystemManager || top.cripFilesystemManager
    }

    if (typeof callback !== 'function') {
      throw new Error('callback method for file select not found!')
    }

    callback(url, settings.params)
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
