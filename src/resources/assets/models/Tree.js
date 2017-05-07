import TreeItem from './TreeItem'

export default class Tree {
  constructor (data) {
    this.items = []

    if (!data || data.length < 1) { return }

    data.forEach(item => {
      this.items.push(new TreeItem(item))
    })
  }
}
