import folderApi from '../api/folder'
import fileApi from '../api/file'

export default class Blob {
  constructor (data) {
    this.name = data.name
    this.extension = data.extension
    this.mime = data.mime
    this.type = data.type
    this.mimetype = data.mimetype
    this.bytes = data.bytes
    this.updated_at = data.updated_at
    this.thumb = data.thumb
    this.dir = data.dir
    this.full_name = data.full_name
    this.url = data.url
    this.size = data.size
    this.thumbs = data.thumbs

    this.$isSelected = false
    this.$edit = false
    this.newName = data.name
    this.$id = `blob-${data.full_name}`
    this.$isSystem = !!data.$isSystem
  }

  get isDir () { return this.type === 'dir' }

  get api () { return this.isDir ? folderApi : fileApi }

  /**
   * Update name of current blob.
   * @returns {Promise.<Blob>}
   */
  update () {
    return new Promise((resolve, reject) => {
      this.api.update(this, this.newName)
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
