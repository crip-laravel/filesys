import folderApi from '../../../api/folder'
import {
  contentLoaded, contentLoading, deselect, changeDir, removeBlob, updateBlob,
  reloadTree
} from './../../mutations'
import { loadContent, changePath, deleteBlob, refresh, saveBlob } from '../../actions'
import { path, selectedBlob, loading } from '../../getters'

export default {
  [loadContent] ({commit, getters}) {
    commit(deselect)
    commit(contentLoading)
    folderApi.content(getters.path)
      .then(items => { commit(contentLoaded, {items, path: getters.path}) })
  },

  [changePath] ({commit, getters}, newPath) {
    if (getters[path] !== newPath && !getters[loading]) {
      commit(deselect)
      commit(contentLoading)
      commit(changeDir, newPath)
      folderApi.content(getters[path])
        .then(items => { commit(contentLoaded, {items, path: getters[path]}) })
    }
  },

  [deleteBlob] ({commit, getters}) {
    let selected = getters[selectedBlob]
    selected.delete().then(() => {
      commit(removeBlob, selected.$id)
      commit(deselect)
    })
  },

  [refresh] ({commit, getters}) {
    commit(deselect)
    commit(contentLoading)
    commit(changeDir, getters[path])
    folderApi.content(getters[path])
      .then(items => { commit(contentLoaded, {items, path: getters[path]}) })
  },

  [saveBlob] ({commit}, blob) {
    blob.save()
      .then(newBlob => {
        commit(updateBlob, {
          id: blob.$id,
          blob: newBlob
        })

        commit(deselect)

        if (blob.isDir) {
          commit(reloadTree)
        }
      })
  }
}
