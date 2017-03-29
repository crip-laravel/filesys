import settings from '../../settings'
import {
  deleteBlob, saveBlob, openBlob, fetchTree, changePath,
  startEditBlob
} from '../actions'
import {
  setUpdatedBlob, setBlobEditMode, removeBlob, setLoadingStarted,
  setLoadingCompleted, setSelectedBlob
} from '../mutations'

const state = {}

const actions = {
  /**
   * Delete blob on server side
   * @param commit
   * @param getters
   * @param dispatch
   */
  [deleteBlob]: ({commit, getters, dispatch}) => {
    commit(setLoadingStarted)
    let selected = getters.selectedBlob
    selected.delete().then(() => {
      commit(removeBlob, selected.$id)
      commit(setLoadingCompleted)

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
    commit(setLoadingStarted)
    blob.save()
      .then(newBlob => {
        commit(setUpdatedBlob, {
          id: blob.$id,
          blob: newBlob
        })

        commit(setSelectedBlob, newBlob)
        commit(setLoadingCompleted)

        if (blob.isDir) {
          dispatch(fetchTree)
        }
      })
  },

  /**
   * Select file or open dir.
   * @param dispatch
   * @param {Blob} blob
   * @param {String} url
   */
  [openBlob]: ({dispatch}, {blob, url}) => {
    if (blob.isDir) {
      return dispatch(changePath, `${blob.dir}/${blob.name}`)
    }

    let action = 'selectCallback'

    if (settings.target() === 'tinymce') {
      action = 'selectTinyMce'
    }

    return dispatch(action, url || blob.url)
  },

  /**
   * Enable edit mode for selected blob.
   * @param commit
   * @param rootState
   */
  [startEditBlob]: ({commit, rootState}) => {
    if (rootState.content.selected && rootState.content.selected.name !== '..') {
      commit(setBlobEditMode)
    }
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

const mutations = {}

const getters = {}

export default {state, actions, mutations, getters}
