import vue from 'vue'
import Tree from '../models/Tree'
import settings from '../settings'

export default {
  /**
   * Receive folders.
   * @returns {Promise.<Tree>}
   */
  getAll () {
    return new Promise((resolve, reject) => {
      vue.http.get(`${settings.treeUrl}`)
        .then(({data}) => { resolve(new Tree(data)) }, reject)
    })
  }
}
