import vue from 'vue'
import Blob from '../models/Blob'
import settings from '../settings'

export default {
  /**
   * Receive folder content.
   * @param {string} path
   * @returns {Promise.<Array.<Blob>>}
   */
  content (path) {
    return new Promise((resolve, reject) => {
      vue.http.get(`${settings.foldersUrl}/${path}`)
        .then(({data}) => {
          let blobs = data.map(blob => new Blob(blob))
          resolve(blobs)
        }, reject)
    })
  },

  /**
   * Create new folder.
   * @param {string} folder
   * @param {string} name
   * @returns {Promise.<Object>}
   */
  create (folder, name) {
    return new Promise((resolve, reject) => {
      vue.http.post(settings.foldersUrl, {folder, name})
        .then(({data}) => { resolve(new Blob(data)) }, reject)
    })
  }
}
