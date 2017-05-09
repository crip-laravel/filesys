import TreeItem from './TreeItem'

export default class Tree {
  constructor (data) {
    this.path = ''
    this.label = 'Home'
    this.children = []

    if (!data || data.length < 1) { return }

    data.forEach(item => {
      this.children.push(new TreeItem(item))
    })
  }
}
