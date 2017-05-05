import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'
import editors from '../../api/editors'
import settings from '../../settings'
import api from '../../api/folder'
import Vue from 'vue'

const state = {
  isCreateFolderBlobVisible: false,
  displayType: 'grid',
  blobs: []
}

const actions = {
  /**
   * Open currently selected blob by its identifier.
   * @param {Function} dispatch Store dispatch action.
   * @param {Boolean} isDir Blob instance.
   * @param {String} customUrl Custom url to be used if we wan`t use custom size
   * of image.
   */
  [a.openBlob]: ({dispatch}, {isDir, path, url}, customUrl = '') => {
    if (isDir) {
      return dispatch(a.changePath, path)
    }

    let action = 'selectCallback'

    if (settings.target() === 'tinymce') {
      action = 'selectTinyMce'
    }

    // TODO: add more editors for FileSysManager

    return editors[action](customUrl || url)
  },

  /**
   * Save blob on the server side.
   * @param {Store} store Vuex store instance.
   * @param {Blob} blob Blob instance to be saved.
   * @returns {Promise}
   */
  [a.saveBlob]: (store, blob) => {
    return new Promise((resolve, reject) => {
      if (blob.name !== blob.$newName) {
        store.commit(m.setLoadingStarted)

        return blob.save()
          .then(newBlob => {
            store.commit(m.setUpdatedBlob, {id: blob.$id, blob: newBlob})
            store.commit(m.setSelectedBlob, newBlob.$id)
            store.commit(m.setLoadingCompleted)

            if (newBlob.isDir) { store.dispatch(a.fetchTree) }
            resolve()
          }).catch(err => {
            store.commit(m.setLoadingCompleted)
            reject(err)
          })
      }

      resolve()
    })
  },

  /**
   * Fetch blob content from the server.
   * @param {Store} store Vuex store instance.
   */
  [a.fetchContent]: (store) => {
    store.commit(m.removeSelectedBlob)
    store.commit(m.setLoadingStarted)

    api.content(store.getters[g.getPath])
      .then(blobs => {
        store.commit(m.setBlobs, blobs)
        store.commit(m.setLoadingCompleted)
      })
  },

  /**
   * Delete blob on the server side and mutate state to remove it from UI.
   * @param {Store} store Vuex store instance.
   * @param {Blob} payload Blob instance witch should be deleted.
   */
  [a.deleteBlob]: (store, payload) => {
    store.commit(m.setLoadingStarted)
    payload.delete()
      .then(() => {
        store.commit(m.removeSelectedBlob)
        store.commit(m.removeBlob, payload.$id)
        store.commit(m.setLoadingCompleted)

        if (payload.isDir) {
          // If blob was a folder, update tree component content as structure
          // changes
          store.dispatch(a.fetchTree)
        }
      })
  }
}

const mutations = {
  /**
   * Mutates state of the isCreateFolderBlobVisible property.
   * @param {state} state State of the store.
   * @param {Boolean} payload
   */
  [m.setCreateFolderBlobVisibility]: (state, payload) => {
    state.isCreateFolderBlobVisible = !!payload
  },

  /**
   * Set rename state of selected blob to true.
   * @param {state} state State of the store.
   */
  [m.setRename]: (state) => {
    let blob = state.blobs.find(b => b.$selected)
    return blob ? Vue.set(blob, '$rename', true) : false
  },

  /**
   * Set state of current display type.
   * @param {state} state State of the store.
   * @param {String} payload
   */
  [m.setDisplayType]: (state, payload) => {
    Vue.set(state, 'displayType', payload)
  },

  /**
   * Set selected blob by its identifier.
   * @param {state} state State of the store.
   * @param {String} payload Blob identifier value.
   */
  [m.setSelectedBlob]: (state, payload) => {
    // disable rename if we are selecting other blob
    setBlobPropertyById(state, payload, '$rename', false, false)

    setBlobPropertyById(state, payload, '$selected', true, false)
  },

  /**
   * Remove selection of any blob in a collection.
   * @param {state} state State of the store.
   */
  [m.removeSelectedBlob]: (state) => {
    setBlobPropertyById(state, '', '$selected', false, false)
  },

  /**
   * Set rename state of blob by its identifier.
   * @param {state} state State of the store.
   * @param {String} payload Blob identifier value.
   */
  [m.setRenameBlob]: (state, payload) => {
    setBlobPropertyById(state, payload, '$rename', true, false)
  },

  /**
   * Set new blob values in place of blob with identifier.
   * @param {state} state State of the store.
   * @param {String} id Blob identifier value.
   * @param {Blob} blob New blob instance.
   */
  [m.setUpdatedBlob]: (state, {id, blob}) => {
    const toUpdate = findBlobById(state, id)
    state.blobs.splice(state.blobs.indexOf(toUpdate), 1)
    state.blobs.push(blob)
  },

  /**
   * Add new blob to store collection.
   * @param {state} state
   * @param {Blob} payload
   */
  [m.setNewBlob]: (state, payload) => {
    state.blobs.push(payload)
  },

  /**
   * Set blobs to the state.
   * @param {state} state State of the store.
   * @param {Array.<Blob>} payload Array of the blobs.
   */
  [m.setBlobs]: (state, payload) => {
    state.blobs = payload
  },

  /**
   * Remove blob from store collection.
   * @param {state} state State of the store.
   * @param {String} payload Te id of the blob to be removed.
   */
  [m.removeBlob]: (state, payload) => {
    const toUpdate = findBlobById(state, payload)
    state.blobs.splice(state.blobs.indexOf(toUpdate), 1)
  }
}

const getters = {
  /**
   * Gets create folder blob visibility state.
   * @param {state} state State of the store.
   * @returns {Boolean} Returns <c>true</c> if create folder blob is in visible
   * state.
   */
  [g.getCreateFolderBlobVisibility]: (state) => state.isCreateFolderBlobVisible,

  /**
   * Gets rename mode from all blobs in store.
   * @param {state} state State of the store.
   * @returns {Boolean} Returns <c>true</c> if any of blob is in state of rename
   * mode.
   */
  [g.getIsAnyBlobInRenameMode]: (state) => state.blobs.filter(b => b.$rename).length > 0,

  /**
   * Gets selected mode from all blobs in store.
   * @param {state} state State of the store.
   * @returns {Boolean} Returns <c>true</c> if any of blob is in state of
   * selected mode.
   */
  [g.getIsAnyBlobInSelectedMode]: (state) => state.blobs.filter(b => b.$selected).length > 0,

  /**
   * Gets current state of display type.
   * @param {state} state State of the store.
   */
  [g.getDisplayType]: (state) => state.displayType,

  /**
   * Gets current state blobs.
   * @param {state} state State of the store.
   */
  [g.getBlobs]: (state) => state.blobs,

  /**
   * Get selected blob instance from the store.
   * @param {state} state State of the store.
   */
  [g.getSelectedBlob]: (state) => state.blobs.find(b => b.$selected)
}

/**
 * TODO: this method should be mowed somewhere
 * Set blob property value by identifier and reset all other to default value.
 * @param {state} state State of the store.
 * @param {String} id Blob identifier value.
 * @param {String} property Property name.
 * @param {*} value Value of the property to be set.
 * @param {*} defaultVal Value of the property of all other blob.
 * @return {Boolean} Boolean indicating whenever value is set to the blob.
 */
function setBlobPropertyById (state, id, property, value, defaultVal) {
  state.blobs.forEach(blob => Vue.set(blob, property, defaultVal))

  let blob = findBlobById(state, id)

  return blob ? Vue.set(blob, property, value) || true : false
}

/**
 * Find blob by identifier in store.
 * @param {state} state State of the store.
 * @param {String} id Blob identifier value.
 * @return {Blob|undefined} Blob instance if it is found in store.
 */
function findBlobById (state, id) {
  return state.blobs.find(blob => blob.$id === id)
}

export default {state, actions, mutations, getters}
