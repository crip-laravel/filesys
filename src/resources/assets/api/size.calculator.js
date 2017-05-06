/**
 * Convert numeric bytes to readable string.
 * @param {Number} bytes Bytes number to be converted.
 * @return {String} Converted bytes string.
 */
export default function (bytes) {
  if (bytes === 1) {
    return bytes + ' byte'
  }

  if (bytes === 0) {
    return '0 bytes'
  }

  let fileSizes = [
    {val: 1, postfix: ' bytes'},
    {val: 1024, postfix: ' kB'},
    {val: 1048576, postfix: ' MB'},
    {val: 1073741824, postfix: ' GB'}
  ]

  let result = ''
  fileSizes.forEach(size => {
    if (bytes >= size.val) {
      result = (bytes / size.val).toFixed(2) + size.postfix
    }
  })

  return result
}
