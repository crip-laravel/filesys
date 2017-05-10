export default class TreeItem {
  constructor ({path, name, children, isOpen = false}) {
    this.path = path
    this.label = name
    this.isOpen = isOpen
    this.children = []

    if (!children || children.length < 1) { return }

    children.forEach(item => {
      this.children.push(new TreeItem(item))
    })
  }
}
