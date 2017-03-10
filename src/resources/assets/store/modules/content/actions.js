import { contentLoaded, contentLoading, deselect, changeDir } from './../../mutations'
import { loadContent, changePath } from '../../actions'
import folderApi from '../../../api/folder'

export default {
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
