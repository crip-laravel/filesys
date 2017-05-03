import * as a from '../actions'
import * as m from '../mutations'
import * as g from '../getters'
import api from '../../api/tree'

let state = {
  items: []
}

let actions = {
  [a.fetchTree]: (state) => {
    api.getAll()
      .then(tree => { state.commit(m.setTree, tree.items) })
  }
}

let mutations = {
  [m.setTree]: (state, payload) => {
    state.items = payload
  }
}

let getters = {
  [g.getTree]: (state) => state.items
}

export default {state, actions, mutations, getters}
