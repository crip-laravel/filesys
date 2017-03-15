export const bodyElement = () => {
  return document.getElementsByTagName('body')[0]
}

export const width = () => {
  return (window.innerWidth ||
    document.documentElement.clientWidth ||
    bodyElement().clientWidth) - 90
}
export const height = () => {
  return (window.innerHeight ||
    document.documentElement.clientHeight ||
    bodyElement().clientHeight) - 90
}
