import folderApi from '../../api/folder'
import { breadcrumb, isLoading, path, pathUp } from '../getters'
import { changePath } from '../actions'
import {
  setLoadingStarted, setLoadingCompleted, setPath,
  removeSelectedBlob, setBlobs
} from '../mutations'

const state = {
  path: '',
  pathUp: '',
  breadcrumb: [],
  isLoading: false
}

const actions = {

  /**
   * Change path and load content from the server.
   * @param commit
   * @param getters
   * @param path
   */
  [changePath]: ({commit, getters}, path) => {
    if (getters.path !== path && !getters.isLoading) {
      let pathUp = path.split('/')
      pathUp.splice(-1, 1)
      pathUp = pathUp.join('/')

      commit(removeSelectedBlob)
      commit(setLoadingStarted)
      folderApi.content(path)
        .then(blobs => {
          commit(setBlobs, {blobs, path, pathUp})
          commit(setLoadingCompleted)
          commit(setPath, path)
        })
    }
  }
}

const mutations = {
  /**
   * Mutate loading state to become active.
   * @param state
   */
  [setLoadingStarted]: (state) => {
    state.isLoading = true
  },

  /**
   * Mutate loading state to become inactive.
   * @param state
   */
  [setLoadingCompleted]: (state) => {
    state.isLoading = false
  },

  /**
   * Mutate path state with new value.
   * @param state
   * @param {String} path
   */
  [setPath]: (state, path) => {
    state.path = path
    state.breadcrumb = path.split('/')

    let parts = path.split('/')
    if (parts.length < 2) {
      state.pathUp = ''
      return
    }

    parts.splice(-1, 1)
    state.pathUp = parts.join('/')
  }
}

const getters = {
  [breadcrumb]: (store) => store.breadcrumb,
  [isLoading]: (store) => store.isLoading,
  [path]: (store) => store.path,
  [pathUp]: (store) => store.pathUp
}

export default {state, actions, mutations, getters}
