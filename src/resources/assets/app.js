import './extensions'
import Vue from 'vue'
import VueResource from 'vue-resource'
import App from './App.vue'
import { store } from './store'
import './sass/app.scss'
import settings from './settings'

Vue.use(VueResource)

Vue.http.interceptors.push((request, next) => {
  let token = settings.params[settings.authorization.web]
  if (token !== undefined) {
    request.headers.set(settings.authorization.web, token)
    request.headers.set(
      settings.authorization.api.key,
      settings.authorization.api.value.supplant(settings.params)
    )
  }
  next()
})

let app = new Vue({store, ...App})

app.$mount('#app')
