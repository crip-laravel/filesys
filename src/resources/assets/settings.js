let settings = document.getElementById('settings')

export default {
  filesUrl: settings.getAttribute('data-files-url'),
  foldersUrl: settings.getAttribute('data-folders-url'),
  treeUrl: settings.getAttribute('data-tree-url'),
  dirIcon: settings.getAttribute('data-dir-icon-url'),
  iconDir: settings.getAttribute('data-icon-dir'),

  icon (name) {
    return `${this.iconDir}${name}.png`
  }
}
