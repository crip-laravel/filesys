import { contentLoaded } from './../index'

const state = {
  isInitialized: false,
  items: []
}

const mutations = {
  [contentLoaded] (state, payload) {
    state.isInitialized = true
    state.items = []
    payload.items.forEach(contentItem => state.items.push(contentItem))
  }
}

export default {state, mutations}
