import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'
import editors from '../../api/editors'
import settings from '../../settings'
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
   */
  [a.openBlob]: ({dispatch}, {isDir, path, url}) => {
    if (isDir) {
      return dispatch(a.changePath, path)
    }

    let action = 'selectCallback'

    if (settings.target() === 'tinymce') {
      action = 'selectTinyMce'
    }

    // TODO: add more editors for FileSysManager

    return editors[action](url)
  },

  /**
   * Rename blob on the server side.
   * @param {store} store State of the store.
   * @param {String} id Blob identifier value.
   * @param {String} name New name for blob.
   */
  [a.renameBlob]: (store, {id, name}) => {
    let blob = findBlobById(store, id)
    if (blob.name !== name) {
      store.commit(m.setLoadingStarted)

      blob.reaname(name)
        .then(newBlob => {
          store.commit(m.setUpdatedBlob, {id, blob: newBlob})
          store.commit(m.setSelectedBlob, id)
          store.commit(m.setLoadingCompleted)

          if (newBlob.isDir) { store.dispatch(a.fetchTree) }
        })
    }
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
    setBlobPropertyById(state, payload, '$selected', true, false)
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
   *
   * @param {state} state State of the store.
   * @param {Array.<Blob>} payload Array of the blobs.
   */
  [m.setBlobs]: (state, payload) => {
    state.blobs = payload
    if (state.getters[g.getPath] === '') { return }

    let blob = new Blob({
      fullName: '..',
      type: 'dir',
      path: state.getters[g.getPathUp],
      thumb: settings.dirIcon,
      mediaType: settings.mediaTypes.dir,
      $isSystem: true
    })

    state.blobs.push(blob)
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
  [g.getDisplayType]: (state) => state.displayType
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

  let blob = findBlobById(id)

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
