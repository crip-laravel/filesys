export default class TreeItem {
  constructor (data) {
    this.path = data.path
    this.name = data.name
    this.children = []

    if (data.children && data.children.length > 0) {
      data.children.forEach(item => {
        this.children.push(new TreeItem(item))
      })
    }
  }
}
