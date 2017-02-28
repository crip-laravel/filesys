import { treeLoaded } from './../index'

const state = {
  isInitialized: false,
  items: []
}

const mutations = {
  [treeLoaded] (state, payload) {
    state.isInitialized = true
    payload.items.forEach(treeItem => state.items.push(treeItem))
  }
}

export default {state, mutations}
