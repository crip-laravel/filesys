import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'
import api from '../../api/file'
import FileForUpload from '../../models/FileForUpload'

const state = {
  uploads: []
}

const actions = {
  /**
   * Add new uploads to the queue.
   * @param {Store} store Vuex store instance.
   * @param {FileList} payload
   */
  [a.addUploads]: (store, payload) => {
    for (let file in payload) {
      if (payload.hasOwnProperty(file)) {
        store.commit(m.setNewUpload, payload[file])
      }
    }
  },

  /**
   * Upload single file to the server.
   * @param {Store} store Vuex store instance.
   * @param {FileForUpload} payload File, to be uploaded from queue.
   */
  [a.uploadFile]: (store, payload) => {
    if (payload.$loading) { return }

    store.commit(m.setUploadFileLoading, payload)
    const path = store.getters[g.getPath]
    api.upload(path, payload.file)
      .then(blob => {
        store.commit(m.removeUpload, payload)
        store.commit(m.setNewBlob, blob)
      })
      .catch(error => {
        store.commit(m.setUploadFileError, {file: payload, error})
      })
  },

  /**
   * Start upload all files from the queue.
   * @param {Store} store Vuex store instance.
   */
  [a.uploadAllFiles]: store => {
    store.state.uploads.forEach(file => {
      store.dispatch(a.uploadFile, file)
    })
  }
}

const mutations = {
  /**
   * Add new file to the uploads queue.
   * @param {state} state The state of vuex store.
   * @param {File} payload File, to be placed in to queue.
   */
  [m.setNewUpload]: (state, payload) => {
    const file = new FileForUpload(payload)
    state.uploads.push(file)
  },

  /**
   * Remove file from uploads queue.
   * @param {state} state The state of vuex store.
   * @param {FileForUpload} payload File, to be removed from queue.
   */
  [m.removeUpload]: (state, payload) => {
    const index = state.uploads.indexOf(payload)
    state.uploads.splice(index, 1)
  },
  /**
   * Set uploading file state to indicate its loading state.
   * @param {state} state The state of vuex store.
   * @param {FileForUpload} payload File, to be removed from queue.
   */
  [m.setUploadFileLoading]: (state, payload) => {
    payload.$loading = true
  },

  /**
   * Set error message for an file in a queue.
   * @param state
   * @param {FileForUpload} file File, to be removed from queue.
   * @param {String} error Error message for a file.
   */
  [m.setUploadFileError]: (state, {file, error}) => {
    file.$error = error
    file.$loading = false
  }
}

const getters = {
  /**
   * Get files for upload from store queue.
   * @param {state} state The sate of store.
   */
  [g.getUploads]: (state) => state.uploads,

  /**
   * Get files count for upload in store queue.
   * @param {state} state The sate of store.
   */
  [g.getUploadsCount]: (state) => state.uploads.length
}

export default {state, actions, mutations, getters}
