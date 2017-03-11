import { contentLoaded, contentLoading, deselect, changeDir, removeBlob } from './../../mutations'
import { loadContent, changePath, deleteBlob } from '../../actions'
import { path, selectedBlob } from '../../getters'
import folderApi from '../../../api/folder'

export default {
  [loadContent] ({commit, getters}) {
    commit(deselect)
    commit(contentLoading)
    folderApi.content(getters.path)
      .then(items => { commit(contentLoaded, {items, path: getters.path}) })
  },

  [changePath] ({commit, getters}, newPath) {
    commit(deselect)
    commit(contentLoading)
    commit(changeDir, newPath)
    folderApi.content(getters[path])
      .then(items => { commit(contentLoaded, {items, path: getters[path]}) })
  },

  [deleteBlob] ({commit, getters}) {
    let selected = getters[selectedBlob]
    selected.delete().then(() => {
      commit(removeBlob, selected.$id)
      commit(deselect)
    })
  }
}
