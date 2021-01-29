export default {
  truncate: (string, length) => {
    let subtract = 3
    let append = '...'
    if (length < 3) {
      subtract = 0
      append = ''
    }
    if (string.length < length) {
      return string
    }
    return string.substring(0, length - subtract) + append
  }
}
