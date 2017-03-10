import { path, loading, blobs, selectedBlob, display } from './../getters'
import { contentLoaded, addItem, selectItem, deselect, setGridView, setListView } from './../mutations'
import { loadContent } from '../actions'
import folderApi from '../../api/folder'

const state = {
  isInitialized: false,
  loading: true,
  breadcrumb: [],
  path: '',
  items: [],
  selectedItem: false,
  display: 'grid'
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
  },

  [addItem] (state, payload) {
    state.items.push(payload.item)
  },

  [selectItem] (state, payload, a) {
    // deselect all items in current dir before select
    // required one
    state.items.forEach(item => {
      item.$isSelected = false
    })

    // make sure that item has a flag about selected
    payload.item.$isSelected = true

    // modify state and make item selected
    state.selectedItem = payload.item
  },

  [deselect] (state) {
    state.items.forEach(item => {
      item.$isSelected = false
    })

    state.selectedItem = false
  },

  [setGridView] (state) {
    state.display = 'grid'
  },

  [setListView] (state) {
    state.display = 'list'
  }
}

const getters = {
  [path]: (store, getters) => store.path,
  [loading]: (store, getters) => store.loading,
  [blobs]: (store) => store.items,
  [selectedBlob]: (store) => store.selectedItem,
  [display]: (store) => store.display
}

export default {state, mutations, getters, actions}
