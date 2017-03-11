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
   * @param {Blob} blob
   * @param {string} name
   * @returns {Promise.<Object>}
   */
  create (blob, name) {
    return new Promise((resolve, reject) => {
      vue.http.post(settings.foldersUrl, {folder: blob.full_name, name})
        .then(({data}) => { resolve(new Blob(data)) }, reject)
    })
  },

  /**
   * Rename folder.
   * @param {Blob} blob
   * @param {string} name
   * @returns {Promise.<Blob>}
   */
  update (blob, name) {
    return new Promise((resolve, reject) => {
      vue.http.patch(`${settings.foldersUrl}/${blob.full_name}`, {name})
        .then(({data}) => { resolve(new Blob(data)) }, reject)
    })
  },

  /**
   * Delete folder.
   * @param {Blob} blob
   * @returns {Promise.<Boolean>}
   */
  delete (blob) {
    return new Promise((resolve, reject) => {
      vue.http.delete(`${settings.foldersUrl}/${blob.full_name}`)
        .then(({data}) => { resolve(!!data) }, reject)
    })
  }
}
