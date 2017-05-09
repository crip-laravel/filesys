import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'
import api from '../../api/tree'
import Tree from '../../models/Tree'

let state = {
  items: [new Tree()]
}

let actions = {
  /**
   * Fetch tree items from the server and apply to the store state.
   * @param {function} commit Store commit action.
   */
  [a.fetchTree]: ({commit}) => {
    commit(m.setLoadingStarted)
    api.getAll()
      .then(tree => {
        commit(m.setTree, tree)
        commit(m.setLoadingCompleted)
      })
  }
}

let mutations = {
  /**
   * Set tree items to the store.
   * @param {state} state State of the store.
   * @param {Tree} payload Collection of tree items.
   */
  [m.setTree]: (state, payload) => {
    state.items = [payload]
  }
}

let getters = {
  /**
   * Get tree items collection.
   * @param {state} state State of the store.
   */
  [g.getTree]: (state) => state.items
}

export default {state, actions, mutations, getters}
