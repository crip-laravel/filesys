import folderApi from '../api/folder'
import fileApi from '../api/file'

/**
 * @property {string} bytes
 * @property {string} dir
 * @property {string} extension
 * @property {string} fullName
 * @property {string} mediaType
 * @property {string} mime
 * @property {string} mimeType
 * @property {string} name
 * @property {string} path
 * @property {string} thumb
 * @property {array} thumbs
 * @property {string} type
 * @property {string} updatedAt
 * @property {string} url
 * @property {string} xs
 */
export default class Blob {
  constructor (data) {
    Object.assign(this, data)

    this.$date = data.updatedAt ? data.updatedAt.dateFromUnixTimestamp() : ''
    this.$id = data.path ? data.path.replaceAll('/', '-') : 'folder-up'
    this.$newName = data.name
    this.$temp = !!data.$temp
    this.$rename = false
    this.$selected = false
  }

  /**
   * Is blob type of folder.
   * @return {boolean}
   */
  get isDir () { return this.type === 'dir' }

  /**
   * Get current blob API class.
   * @return {folderApi|fileApi}
   */
  get api () { return this.isDir ? folderApi : fileApi }

  /**
   * Save changes of blob on the server side.
   * @returns {Promise.<Blob>}
   */
  save () {
    let action = 'update'

    if (this.$temp) {
      action = 'create'
    }

    return new Promise((resolve, reject) => {
      this.api[action](this, this.$newName)
        .then(
          blob => resolve(blob),
          err => reject(err.data))
    })
  }

  /**
   * Delete current blob on the server side.
   * @returns {Promise.<Boolean>}
   */
  delete () {
    return new Promise((resolve, reject) => {
      this.api.delete(this)
        .then(state => resolve(state), reject)
    })
  }
}
