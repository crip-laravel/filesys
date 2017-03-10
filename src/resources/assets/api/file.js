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
      vue.http.patch(`${settings.filesUrl}/${blob.full_name}`, {name})
        .then(({data}) => { resolve(new Blob(data)) }, reject)
    })
  }
}
