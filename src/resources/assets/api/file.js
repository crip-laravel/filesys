import vue from 'vue'
import Blob from '../models/Blob'
import settings from '../settings'

export default {
  /**
   * Rename folder.
   * @param {Blob} blob
   * @param {string} name
   * @returns {Promise.<Blob>}
   */
  update (blob, name) {
    return new Promise((resolve, reject) => {
      vue.http.patch(`${settings.filesUrl}/${blob.path}`, {name})
        .then(({data}) => { resolve(new Blob(data)) }, reject)
    })
  },

  /**
   * Delete file.
   * @param {Blob} blob
   * @returns {Promise.<Boolean>}
   */
  delete (blob) {
    return new Promise((resolve, reject) => {
      vue.http.delete(`${settings.filesUrl}/${blob.path}`)
        .then(({data}) => { resolve(!!data) }, reject)
    })
  },
  /**
   * Upload file to the server API.
   * @param path
   * @param file
   * @return {Promise}
   */
  upload (path, file) {
    let formData = new FormData()
    formData.append('file', file)
    formData.append('path', path)

    return new Promise((resolve, reject) => {
      vue.http.post(`${settings.filesUrl}`, formData)
        .then(
          ({data}) => resolve(new Blob(data)),
          ({status, data}) => {
            status === 422 ? reject(data) : reject('Could not upload file.')
          })
    })
  }
}
