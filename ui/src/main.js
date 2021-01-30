import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import Api from './api'

Vue.config.productionTip = false

Vue.prototype.$api = new Api(store, router)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
