import { fetchTree } from '../actions'
import { treeFolders } from '../getters'
import { setTreeFolders } from '../mutations'
import treeApi from '../../api/tree'

const state = {
  folders: []
}

const actions = {
  /**
   * Fetch tree folders from server.
   * @param commit
   */
  [fetchTree]: ({commit}) => {
    treeApi.getAll()
      .then(tree => commit(setTreeFolders, tree.items))
  }
}

const mutations = {
  /**
   * Mutate folders with new items.
   * @param state
   * @param folders
   */
  [setTreeFolders]: (state, folders) => {
    state.folders = folders
  }
}

const getters = {
  [treeFolders]: (state) => state.folders
}

export default {state, actions, mutations, getters}
