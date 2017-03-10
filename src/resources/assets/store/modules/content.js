import { path, loading, blobs, selectedBlob, display } from './../getters'
import {
  contentLoaded, addItem, selectItem, deselect, setGridView, setListView,
  enableEdit, updateBlob
} from './../mutations'
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

  [addItem] (state, blob) {
    state.items.push(blob)
  },

  [selectItem] (state, blob) {
    // deselect all items in current dir before select
    // required one
    deselectItems(state)

    // make sure that item has a flag about selected
    blob.$isSelected = true

    // modify state and make item selected
    state.selectedItem = blob
  },

  [enableEdit] (state) {
    if (state.selectedItem) {
      state.selectedItem.$edit = true
      setTimeout(() => document.getElementById(state.selectedItem.$id).focus(), 1)
    }
  },

  [deselect] (state) {
    deselectItems(state)
  },

  [setGridView] (state) {
    state.display = 'grid'
  },

  [setListView] (state) {
    state.display = 'list'
  },

  [updateBlob] (state, {id, blob}) {
    let toUpdate = state.items.filter(b => b.$id === id)[0]
    state.items.splice(state.items.indexOf(toUpdate), 1)
    state.items.push(blob)
  }
}

const getters = {
  [path]: (store, getters) => store.path,
  [loading]: (store, getters) => store.loading,
  [blobs]: (store) => store.items,
  [selectedBlob]: (store) => store.selectedItem,
  [display]: (store) => store.display
}

/** Helper methods to avoid code duplicates */

function deselectItems (state) {
  state.items.forEach(item => {
    item.$isSelected = false
    item.$edit = false
  })

  state.selectedItem = false
}

export default {state, mutations, getters, actions}
