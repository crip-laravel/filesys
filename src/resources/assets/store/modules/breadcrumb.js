import * as a from '../actions'
import * as g from '../getters'
import * as m from '../mutations'

const state = {
  loading: 0,
  path: '' // TODO: get initial path from router
}

const actions = {
  /**
   *
   * @param {state} store State of the store.
   * @param {String} payload New path value.
   */
  [a.changePath]: (store, payload) => {
    const path = payload.trim('/')

    // Avoid any mutation if path already loaded or some loading is not
    // completed yet.
    if (store.path === path || store.getters[g.isLoading]) return

    store.commit(m.setPath, path)

    store.dispatch(a.fetchContent)
  }
}

const mutations = {
  /**
   * Increase loading properties count in a store.
   * @param {state} state State of the store.
   */
  [m.setLoadingStarted]: (state) => {
    state.loading++
  },

  /**
   * Decrease loading properties count in a store.
   * @param {state} state State of the store.
   */
  [m.setLoadingCompleted]: (state) => {
    state.loading--
  },

  /**
   * Update path property value.
   * @param {state} state State of the store.
   * @param {String} payload Path value to be set.
   */
  [m.setPath]: (state, payload) => {
    state.path = payload
  }
}

const getters = {
  /**
   * Gets page loading indicator state.
   * @param {state} state State of the store.
   * @return {Boolean} State of the isLoading store property.
   */
  [g.isLoading]: (state) => state.loading > 0,

  /**
   * Get current path property value.
   * @param state
   */
  [g.getPath]: (state) => state.path,

  /**
   * Get path up property value of the state.
   * @param {state} state State of the store.
   * @return {String}
   */
  [g.getPathUp]: (state) => {
    let parts = state.path.split('/')

    if (parts.length < 2) { return '' }

    parts.splice(-1, 1)

    return parts.join('/')
  }
}

export default {state, actions, mutations, getters}
