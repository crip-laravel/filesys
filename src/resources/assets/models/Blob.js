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
  }

  get isDir () { return this.type === 'dir' }
}
