import vue from 'vue'
import TreeItem from '../models/TreeItem'
import settings from '../settings'

export default {
  /**
   * Receive folders.
   * @returns {Promise.<Tree>}
   */
  getAll () {
    return new Promise((resolve, reject) => {
      vue.http.get(`${settings.treeUrl}`)
        .then(({data: children}) => {
          resolve(new TreeItem({
            name: 'Home',
            path: '',
            isOpen: true,
            children
          }))
        }, reject)
    })
  }
}
