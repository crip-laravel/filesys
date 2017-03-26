import settings from '../settings'

export default class FileUpload {
  /**
   * Initialize new instance of file upload.
   * @param {File} file
   */
  constructor (file) {
    this.file = file
    this.reader = new FileReader()
    this.src = settings.icon('file')

    this.reader.onload = (e) => {
      this.src = e.target.result
    }

    this.reader.readAsDataURL(file)
  }
}
