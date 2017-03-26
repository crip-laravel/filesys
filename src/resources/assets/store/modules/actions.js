import Blob from '../../models/Blob'
import fileApi from '../../api/file'
import FileUpload from '../../models/FileUpload'

import {
  isEditEnabled, path, creating, uploads, selectedBlob, uploadsCount
} from '../getters'

import {
  setSelectedBlob, setNewBlob, setCreateEnabled, setNewUpload, removeUpload
} from '../mutations'

import {
  openCreateFolderDialog, startEditBlob, filesForUploadAdded, startUpload
} from '../actions'

const state = {
  uploads: []
}

const actions = {
  /**
   * Open create dialog for folder.
   * @param {Function} commit
   * @param {Function} getters
   * @param {Function} dispatch
   */
  [openCreateFolderDialog]: ({commit, getters, dispatch}) => {
    if (!getters[creating]) {
      let dirToCreate = new Blob({
        $isSystem: true,
        $temp: true,
        full_name: getters[path],
        name: 'New-Name',
        type: 'dir'
      })

      commit(setSelectedBlob, dirToCreate)
      commit(setNewBlob, dirToCreate)
      dispatch(startEditBlob)
      commit(setCreateEnabled)
    }
  },

  /**
   * Add new files for upload.
   * @param {Function} commit
   * @param {Event} e
   */
  [filesForUploadAdded]: ({commit}, e) => {
    let files = e.target.files || e.dataTransfer.files
    if (files.length < 1) {
      return
    }

    for (let key in files) {
      if (files.hasOwnProperty(key)) {
        commit(setNewUpload, files[key])
      }
    }
  },

  /**
   * Start upload files from the queue.
   * @param {Function} commit
   * @param {Object} getters
   */
  [startUpload]: ({commit, state, getters}) => {
    state.uploads.forEach(f => {
      fileApi.upload(getters[path], f.file)
        .then(blob => {
          commit(removeUpload, f)
          commit(setNewBlob, blob)
        })
    })
  }
}

const mutations = {
  /**
   * Set new file for uploading.
   * @param {Object} state
   * @param {File} file
   */
  [setNewUpload]: (state, file) => {
    state.uploads.push(new FileUpload(file))
  },

  /**
   * Remove file from uploads collection.
   * @param {Object} state
   * @param {FileUpload} fileUpload
   */
  [removeUpload]: (state, fileUpload) => {
    state.uploads.splice(state.uploads.indexOf(fileUpload), 1)
  }
}

const getters = {
  /**
   * Gets selected blob edit mode state.
   * @param {Object} state
   * @returns {Boolean}
   */
  [isEditEnabled]: (state, getters) => {
    return getters[selectedBlob] && getters[selectedBlob].$edit
  },

  /**
   * Gets active uploads file collection.
   * @param {Object} state
   * @returns {Array}
   */
  [uploads]: state => state.uplaods,

  /**
   * Gets count of uploads.
   * @param {Object} state
   */
  [uploadsCount]: state => state.uploads.length
}

export default {state, actions, mutations, getters}
