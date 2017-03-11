import {
  contentLoaded, contentLoading, addItem, selectItem, deselect, setGridView, setListView,
  enableEdit, updateBlob, changeDir, removeBlob
} from '../../mutations'
import settings from '../../../settings'
import Blob from '../../../models/Blob'

export default {
  [contentLoaded] (state, payload) {
    state.loading = false
    state.items = payload.items

    if (payload.path !== '') {
      state.items.push(new Blob({
        name: '..',
        type: 'dir',
        full_name: state.pathUp,
        thumb: settings.dirIcon,
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
    if (parts.length < 2) {
      state.pathUp = ''
      return
    }

    parts.splice(-1, 1)
    state.pathUp = parts.join('/')
  },

  [removeBlob] (state, blobId) {
    let toRemove = state.items.filter(b => b.$id === blobId)[0]
    state.items.splice(state.items.indexOf(toRemove), 1)
  }
}

/** Helper methods to avoid code duplicates */

function deselectItems (state) {
  state.items.forEach(item => {
    item.$isSelected = false
    item.$edit = false
  })

  state.selectedItem = false
}
