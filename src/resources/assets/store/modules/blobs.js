// import * as a from '../actions'
// import settings from '../../settings'
import * as g from '../getters'
import * as m from '../mutations'
import Vue from 'vue'

const state = {
  isCreateFolderBlobVisible: false,
  blobs: []
}

const actions = {}

const mutations = {
  /**
   * Mutates state of the isCreateFolderBlobVisible property.
   * @param {state} state
   * @param {Boolean} payload
   */
  [m.setCreateFolderBlobVisibility]: (state, payload) => {
    state.isCreateFolderBlobVisible = !!payload
  },

  /**
   * Set rename state of selected blob to true.
   * @param {state} state
   */
  [m.setRename]: (state) => {
    let blob = state.blobs.find(b => b.$selected)
    return blob ? Vue.set(blob, '$rename', true) : false
  }
}

const getters = {
  /**
   * Gets create folder blob visibility state.
   * @param {state} state
   * @returns {Boolean} Returns <c>true</c> if create folder blob is in visible
   * state.
   */
  [g.getCreateFolderBlobVisibility]: (state) => state.isCreateFolderBlobVisible,

  /**
   * Gets rename mode from all blobs in store.
   * @param {state} state
   * @returns {Boolean} Returns <c>true</c> if any of blob is in state of rename
   * mode.
   */
  [g.getIsAnyBlobInRenameMode]: (state) => state.blobs.filter(b => b.$rename).length > 0,

  /**
   * Gets selected mode from all blobs in store.
   * @param {state} state
   * @returns {Boolean} Returns <c>true</c> if any of blob is in state of
   * selected mode.
   */
  [g.getIsAnyBlobInSelectedMode]: (state) => state.blobs.filter(b => b.$selected).length > 0
}

export default {state, actions, mutations, getters}
