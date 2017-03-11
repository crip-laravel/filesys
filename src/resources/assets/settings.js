let settings = document.getElementById('settings')

export default {
  filesUrl: settings.getAttribute('data-files-url'),
  foldersUrl: settings.getAttribute('data-folders-url'),
  dirIcon: settings.getAttribute('data-dir-icon-url')
}
