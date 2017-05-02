import Vue from 'vue'
import Vuex from 'vuex'
import actions from './modules/actions'
import blob from './modules/blob'
import blobs from './modules/blobs'
import breadcrumb from './modules/breadcrumb'
import content from './modules/content'
import path from './modules/path'
import tree from './modules/tree'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {actions, content, blob, path, tree, blobs, breadcrumb}
})
