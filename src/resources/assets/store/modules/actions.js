import { isEditEnabled } from '../getters'

const state = {}

const actions = {}

const mutations = {}

const getters = {
  /**
   * Gets selected blob edit mode state.
   * @param state
   * @returns {boolean}
   */
  [isEditEnabled]: (state, getters, rootState) => {
    return rootState.selected && rootState.selected.$edit
  }
}

export default {state, actions, mutations, getters}
