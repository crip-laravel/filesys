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
