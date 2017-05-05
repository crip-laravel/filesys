import * as g from '../getters'

const state = {
  uploads: []
}

const actions = {}

const mutations = {}

const getters = {
  /**
   * Get files for upload from store queue.
   * @param {state} state The sate of store.
   */
  [g.getUploads]: (state) => state.uploads
}

export default {state, actions, mutations, getters}
