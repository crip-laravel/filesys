import Vue from 'vue'
import Vuex from 'vuex'
import content from './modules/content'

Vue.use(Vuex)

export const store = new Vuex.Store({
  ...content
})
