import vue from 'vue'
import settings from '../settings'

export default {
  /**
   * Receive folder content.
   * @param path
   * @returns {Promise.<Array>}
   */
  content (path) {
    return new Promise((resolve, reject) => {
      vue.http.get(`${settings.foldersUrl}/${path}`)
        .then(({data}) => {
          resolve(data)
        }, reject)
    })
  }
}
