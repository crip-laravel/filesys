import settings from '../settings'
import Vue from 'vue'

export default class FileUpload {
  /**
   * Initialize new instance of file upload.
   * @param {File} file
   */
  constructor (file) {
    this.file = file
    this.name = file.name
    this.reader = new FileReader()
    this.src = settings.icon('file')
    this.$loading = false
    this.$error = ''

    this.reader.onload = (e) => {
      // Mutate content only for images, all other files should show default
      // icon of the file
      if (e.target.result && e.target.result.startsWith('data:image')) {
        Vue.set(this, 'src', e.target.result)
      }
    }

    this.reader.readAsDataURL(file)
  }

  get hasError () { return !!this.$error }

  get title () { return this.hasError ? this.$error : this.name }
}
