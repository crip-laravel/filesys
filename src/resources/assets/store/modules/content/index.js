const state = {
  loading: true,
  breadcrumb: [],
  path: '',
  pathUp: '',
  items: [],
  selectedItem: false,
  creating: false,
  display: 'grid'
}

import actions from './actions'
import mutations from './mutations'
import getters from './getters'

export default {state, actions, mutations, getters}
