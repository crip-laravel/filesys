import { path, loading, blobs, selectedBlob, display } from '../../getters'

export default {
  [path]: (store, getters) => store.path,
  [loading]: (store, getters) => store.loading,
  [blobs]: (store) => store.items,
  [selectedBlob]: (store) => store.selectedItem,
  [display]: (store) => store.display
}
