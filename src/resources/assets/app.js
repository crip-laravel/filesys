import './extensions'
import Vue from 'vue'
import VueResource from 'vue-resource'
import App from './App.vue'
import { store } from './store'
import './sass/app.scss'
import settings from './settings'

Vue.use(VueResource)

Vue.http.interceptors.push((request, next) => {
  Object.keys(settings.params).forEach(key => {
    request.headers.set(key, settings.params[key])
  })
  next()
})

let app = new Vue({store, ...App})

app.$mount('#app')
