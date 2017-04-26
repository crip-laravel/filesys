import Vue from 'vue'
import Vuex from 'vuex'
import actions from './modules/actions'
import content from './modules/content'
import blob from './modules/blob'
import blobs from './modules/blobs'
import path from './modules/path'
import tree from './modules/tree'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {actions, content, blob, path, tree, blobs}
})
