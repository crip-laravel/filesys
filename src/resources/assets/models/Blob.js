import folderApi from '../api/folder'
import fileApi from '../api/file'

/**
 * @property {array} thumbs
 * @property {string} bytes
 * @property {string} bytes
 * @property {string} dir
 * @property {string} extension
 * @property {string} full_name
 * @property {string} mediatype
 * @property {string} mime
 * @property {string} mimetype
 * @property {string} name
 * @property {string} size
 * @property {string} thumb
 * @property {string} type
 * @property {string} updated_at
 * @property {string} url
 */
export default class Blob {
  constructor (data) {
    Object.assign(this, data)

    this.$edit = false
    this.$temp = !!data.$temp
    this.$id = `blob-${data.full_name}`
    this.newName = data.name
  }

  get isDir () { return this.type === 'dir' }

  get api () { return this.isDir ? folderApi : fileApi }

  /**
   * Update name of current blob.
   * @returns {Promise.<Blob>}
   */
  save () {
    let action = 'update'

    if (this.$temp) {
      action = 'create'
    }

    return new Promise((resolve, reject) => {
      this.api[action](this, this.newName)
        .then(blob => resolve(blob), reject)
    })
  }

  /**
   * Delete current blob.
   * @returns {Promise.<Boolean>}
   */
  delete () {
    return new Promise((resolve, reject) => {
      this.api.delete(this)
        .then(state => resolve(state), reject)
    })
  }
}
