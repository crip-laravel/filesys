import Vue from 'vue'
import Vuex from 'vuex'
import tree from './modules/tree'
import content from './modules/content'

Vue.use(Vuex)

export const store = new Vuex.Store({
  modules: {tree, content}
})
