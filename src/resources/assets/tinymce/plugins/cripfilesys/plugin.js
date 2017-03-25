import { width, height } from './../../helpers'

tinymce.PluginManager.requireLangPack('cripfilesys', 'en_GB,lv,ru')

tinymce.PluginManager.add('cripfilesys', (editor) => {
  function OpenCripFilesystemManager () {
    let file = editor.settings.external_filemanager_path + '?target=tinymce'
    let title = 'Crip Filesystem Manager'
    editor.focus(true)

    editor.windowManager.open({
      title,
      file,
      width: width(),
      height: height()
    }, {
      setUrl: (selectedUrl) => {
        editor.insertContent(editor.convertURL(selectedUrl))
      }
    })
  }

  editor.addButton('cripfilesys-btn', {
    icon: 'browse',
    tooltip: 'Insert file',
    shortcut: 'Ctrl+E',
    onclick: OpenCripFilesystemManager
  })

  editor.addShortcut('Ctrl+E', '', OpenCripFilesystemManager)

  editor.addMenuItem('cripfilesys-menu-btn', {
    icon: 'browse',
    text: 'Insert file',
    shortcut: 'Ctrl+E',
    onclick: OpenCripFilesystemManager,
    context: 'insert'
  })
})
