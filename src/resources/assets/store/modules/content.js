import { path, loading } from './../getters'
import { contentLoaded } from './../mutations'
import { loadContent } from '../actions'
import folderApi from '../../api/folder'

const state = {
  isInitialized: false,
  loading: true,
  breadcrumb: [],
  path: '',
  items: []
}

const actions = {
  [loadContent] ({commit, getters}) {
    folderApi.content(getters.path)
      .then(items => { commit(contentLoaded, {items}) })
  }
}

const mutations = {
  [contentLoaded] (state, payload) {
    state.isInitialized = true
    state.loading = false
    state.items = payload.items
  }
}

const getters = {
  [path]: (store, getters) => store.path,
  [loading]: (store, getters) => store.loading
}

export default {state, mutations, getters, actions}
