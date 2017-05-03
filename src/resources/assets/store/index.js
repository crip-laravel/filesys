import blobs from './modules/blobs'
import breadcrumb from './modules/breadcrumb'
import tree from './modules/tree'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {blobs, breadcrumb, tree}
})
