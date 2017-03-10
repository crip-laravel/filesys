import { path, loading, blobs, selectedBlob, display } from './../getters'
import {
  contentLoaded, contentLoading, addItem, selectItem, deselect, setGridView, setListView,
  enableEdit, updateBlob, changeDir
} from './../mutations'
import { loadContent, changePath } from '../actions'
import folderApi from '../../api/folder'
import Blob from '../../models/Blob'

const state = {
  loading: true,
  breadcrumb: [],
  path: '',
  pathUp: '',
  items: [],
  selectedItem: false,
  display: 'grid'
}

const actions = {
  [loadContent] ({commit, getters}) {
    commit(deselect)
    commit(contentLoading)
    folderApi.content(getters.path)
      .then(items => { commit(contentLoaded, {items, path: getters.path}) })
  },

  [changePath] ({commit, getters}, path) {
    commit(deselect)
    commit(contentLoading)
    commit(changeDir, path)
    folderApi.content(getters.path)
      .then(items => { commit(contentLoaded, {items, path: getters.path}) })
  }
}

const mutations = {
  [contentLoaded] (state, payload) {
    state.loading = false
    state.items = payload.items

    if (payload.path !== '') {
      state.items.push(new Blob({
        name: '..',
        type: 'dir',
        full_name: state.pathUp,
        $isSystem: true
      }))
    }
  },

  [contentLoading] (state) {
    state.loading = true
  },

  [addItem] (state, blob) {
    state.items.push(blob)
  },

  [selectItem] (state, blob) {
    if (blob.$id !== state.selectedItem.$id && !blob.$isSystem) {
      // deselect all items in current dir before select
      // required one
      deselectItems(state)

      // make sure that item has a flag about selected
      blob.$isSelected = true

      // modify state and make item selected
      state.selectedItem = blob
    }
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
  },

  [changeDir] (state, path) {
    state.path = path
    state.breadcrumb = path.split('/')

    let parts = path.split('/')
    if (parts.length < 3) {
      if (parts.length < 2) {
        state.pathUp = ''
      } else {
        state.pathUp = parts[0]
      }

      return
    }

    state.pathUp = path.split('/').splice(-1, 2).join('/')
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
