// import settings from '../../settings'
// import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'

const state = {
  isCreateFolderBlobVisible: false
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
  }
}

const getters = {
  /**
   * Gets create folder blob visibility state.
   * @param {state} state
   * @returns {Boolean} Returns <c>true</c> if create folder blob is in visible
   * state.
   */
  [g.getCreateFolderBlobVisibility]: (state) => state.isCreateFolderBlobVisible
}

export default {state, actions, mutations, getters}
