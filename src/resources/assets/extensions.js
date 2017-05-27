function escapeRegExp (str) {
  return str.replace(/([.*+?^=!:${}()|[]\/\\])/g, '\\$1')
}

function toDatePart (number) {
  return `0${number}`.substr(-2)
}

if (!String.prototype.replaceAll) {
  String.prototype.replaceAll = function (find, replace) {
    return this.replace(new RegExp(escapeRegExp(find), 'g'), replace)
  }
}

if (!Number.prototype.dateFromUnixTimestamp) {
  Number.prototype.dateFromUnixTimestamp = function () {
    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
    let date = new Date(this * 1000)
    let year = date.getFullYear()
    let month = toDatePart(date.getMonth() + 1)
    let day = toDatePart(date.getDate())
    let minutes = toDatePart(date.getMinutes())
    let hours = date.getHours()

    // Will display date in 2017/01/01 10:30 format
    return `${year}/${month}/${day} ${hours}:${minutes}`
  }
}

if (!String.prototype.supplant) {
  /**
   * supplant() does variable substitution on the string. It scans through the
   * string looking for expressions enclosed in { } braces. If an expression is
   * found, use it as a key on the object, and if the key has a string value or
   * number value, it is substituted for the bracket expression and it repeats.
   */
  String.prototype.supplant = function (o) {
    return this.replace(
      /{([^{}]*)}/g,
      function (a, b) {
        let r = o[b]
        return typeof r === 'string' || typeof r === 'number' ? r : a
      }
    )
  }
}
