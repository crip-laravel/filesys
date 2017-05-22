import settings from '../settings'

export default {
  /**
   * Select file for tinyMCE.
   * @param {String} url
   */
  selectTinyMce (url) {
    if (!top.tinymce) {
      throw new Error('tinyMCE is selected as target, but `window.top` does not contain it!')
    }

    let wManager = top.tinymce.activeEditor.windowManager

    if (top.tinymce.majorVersion < 4) {
      wManager.params.setUrl(url)
      wManager.close(wManager.params.mce_window_id)
    } else {
      wManager.getParams().setUrl(url)
      wManager.close()
    }
  },

  /**
   * Select file for CKEditor.
   * @param {String} url
   * @param {String} altText
   */
  selectCKEditor (url, altText) {
    if (!window.opener || !window.opener.CKEDITOR) {
      throw new Error('CKEDITOR is selected as target, but `window.opener` does not contain it!')
    }

    const funcNum = settings.params.CKEditorFuncNum
    window.opener.CKEDITOR.tools.callFunction(funcNum, url, function () {
      // Get the reference to a dialog window.
      let dialog = this.getDialog()
      // Check if this is the Image Properties dialog window.
      if (dialog.getName() === 'image') {
        // Get the reference to a text field that stores the "alt" attribute.
        let element = dialog.getContentElement('info', 'txtAlt')
        // Assign the new value.
        if (element) {
          element.setValue(altText)
        }
      }
    })
    window.close()
  },

  /**
   * Select url for user callback.
   * @param {String} url
   */
  selectCallback (url) {
    let userCallback = settings.callback()
    let callback = _ => _

    if (userCallback) {
      callback = window[userCallback] || parent[userCallback] || top[userCallback]
    } else {
      callback = window.cripFilesystemManager || parent.cripFilesystemManager || top.cripFilesystemManager
    }

    if (typeof callback !== 'function') {
      throw new Error('callback method for file select not found!')
    }

    callback(url, settings.params)
  }
}
