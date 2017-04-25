import Blob from '../../models/Blob'
import fileApi from '../../api/file'
import FileUpload from '../../models/FileUpload'
import Vue from 'vue'

import {
  isEditEnabled, path, creating, uploads, selectedBlob, uploadsCount
} from '../getters'

import {
  setSelectedBlob, setNewBlob, setCreateEnabled, setNewUpload, removeUpload,
  setFileUploadLoading, setUploadError
} from '../mutations'

import {
  openCreateFolderDialog, startEditBlob, filesForUploadAdded, startUpload,
  uploadFile
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
   * @param {FileList} files
   */
  [filesForUploadAdded]: ({commit}, files) => {
    for (let key in files) {
      if (files.hasOwnProperty(key)) {
        commit(setNewUpload, files[key])
      }
    }
  },

  /**
   * Start upload files from the queue.
   * @param {Object} state
   * @param {Function} dispatch
   */
  [startUpload]: ({state, dispatch}) => {
    state.uploads.forEach(file => {
      dispatch(uploadFile, file)
    })
  },

  /**
   * Start upload single file.
   * @param {Function} commit
   * @param {Object} getters
   * @param {FileUpload} fileUpload
   */
  [uploadFile]: ({commit, getters}, fileUpload) => {
    if (fileUpload.$loading) {
      // File already is uploading
      return
    }
    commit(setFileUploadLoading, fileUpload)
    fileApi.upload(getters[path], fileUpload.file)
      .then(blob => {
        commit(removeUpload, fileUpload)
        commit(setNewBlob, blob)
      }, errors => {
        commit(setUploadError, {file: fileUpload, error: errors.join(' ')})
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
  },

  /**
   * Mark file as loading.
   * @param {Object} state
   * @param {FileUpload} fileUpload
   */
  [setFileUploadLoading]: (state, fileUpload) => {
    Vue.set(fileUpload, '$loading', true)
  },

  /**
   * Set file upload error state.
   * @param {Object} state
   * @param {FileUpload} file
   * @param {String} error
   */
  [setUploadError]: (state, {file, error}) => {
    Vue.set(file, '$error', error)
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
  [uploads]: state => state.uploads,

  /**
   * Gets count of uploads.
   * @param {Object} state
   */
  [uploadsCount]: state => state.uploads.length
}

export default {state, actions, mutations, getters}
