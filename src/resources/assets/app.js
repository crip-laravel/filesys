import './extensions'
import Vue from 'vue'
import VueResource from 'vue-resource'
import App from './App.vue'
import { store } from './store'
import './sass/app.scss'

Vue.use(VueResource)

let app = new Vue({store, ...App})

app.$mount('#app')
