import Vue from 'vue'

/**
 * Set blob property value by identifier and reset all other to default value.
 * @param {state} state State of the store.
 * @param {String} id Blob identifier value.
 * @param {String} property Property name.
 * @param {*} value Value of the property to be set.
 * @param {*} defaultVal Value of the property of all other blob.
 * @return {Boolean} Boolean indicating whenever value is set to the blob.
 */
export function setBlobPropertyById (state, id, property, value, defaultVal) {
  state.blobs.forEach(blob => Vue.set(blob, property, defaultVal))

  let blob = findBlobById(state, id)

  return blob ? Vue.set(blob, property, value) || true : false
}

/**
 * Find blob by identifier in store.
 * @param {state} state State of the store.
 * @param {String} id Blob identifier value.
 * @return {Blob|undefined} Blob instance if it is found in store.
 */
export function findBlobById (state, id) {
  return state.blobs.find(blob => blob.$id === id)
}
