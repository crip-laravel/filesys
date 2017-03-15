import { height, width } from './helpers'

tinymce.PluginManager.add('filemanager', function (editor) {
  function CripFileBrowser (fieldName, url, type, win) {
    const getParams = `?target=tinymce&type=${type}`

    editor.windowManager.open({
      file: editor.settings.external_filemanager_path + getParams,
      title: 'CRIP Filesystem manager',
      width: width(),
      height: height()
    }, {
      setUrl: (selectedUrl) => {
        let input = win.document.getElementById(fieldName)
        input.value = editor.convertURL(selectedUrl)

        if ('createEvent' in document) {
          let event = document.createEvent('HTMLEvents')
          event.initEvent('change', !1, !0)
          return input.dispatchEvent(event)
        }

        return input.fireEvent('onchange')
      }
    })
  }

  return (tinymce.activeEditor.settings.file_browser_callback = CripFileBrowser)
})
