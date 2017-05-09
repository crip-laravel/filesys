import './extensions'
import Vue from 'vue'
import VueResource from 'vue-resource'
import ElementUI from 'element-ui'
import App from './App.vue'
import { store } from './store'
import './sass/app.scss'

Vue.use(VueResource)
Vue.use(ElementUI)

let app = new Vue({store, ...App})

app.$mount('#app')
