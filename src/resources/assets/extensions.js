function escapeRegExp (str) {
  return str.replace(/([.*+?^=!:${}()|[]\/\\])/g, '\\$1')
}

if (!String.prototype.replaceAll) {
  String.prototype.replaceAll = function (find, replace) {
    return this.replace(new RegExp(escapeRegExp(find), 'g'), replace)
  }
}
