import { path, loading, blobs, selectedBlob, display, breadcrumb, creating } from '../../getters'

export default {
  [blobs]: (store) => store.items,
  [breadcrumb]: (store) => store.breadcrumb,
  [creating]: (store) => store.creating,
  [display]: (store) => store.display,
  [loading]: (store, getters) => store.loading,
  [path]: (store, getters) => store.path,
  [selectedBlob]: (store) => store.selectedItem
}
