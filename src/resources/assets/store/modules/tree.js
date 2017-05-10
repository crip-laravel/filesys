import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'
import api from '../../api/tree'
import TreeItem from '../../models/TreeItem'

let state = {
  tree: new TreeItem({name: 'home', path: ''})
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
   * Set tree root item in the store.
   * @param {state} state State of the store.
   * @param {Tree} payload Collection of tree items.
   */
  [m.setTree]: (state, payload) => {
    state.tree = payload
  }
}

let getters = {
  /**
   * Gets tree root item.
   * @param {state} state State of the store.
   */
  [g.getTree]: (state) => state.tree
}

export default {state, actions, mutations, getters}
