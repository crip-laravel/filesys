let settings = document.getElementById('settings')
const mediaTypes = {
  file: 'file',
  image: 'image'
}

export default {
  filesUrl: settings.getAttribute('data-files-url'),
  foldersUrl: settings.getAttribute('data-folders-url'),
  treeUrl: settings.getAttribute('data-tree-url'),
  dirIcon: settings.getAttribute('data-dir-icon-url'),
  iconDir: settings.getAttribute('data-icon-dir'),
  params: JSON.parse(settings.getAttribute('data-params').replaceAll('\'', '"')),

  icon (name) {
    return `${this.iconDir}${name}.png`
  },

  target () {
    if (this.params && this.params.target) {
      return this.params.target.toLowerCase()
    }

    return 'input'
  },

  mediaType () {
    if (this.params && this.params.type && mediaTypes[this.params.type]) {
      return this.params.type
    }

    return mediaTypes.file
  },

  callback () {
    if (this.params && this.params.callback) {
      return this.params.callback
    }

    return false
  }
}
