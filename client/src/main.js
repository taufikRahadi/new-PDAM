import Vue from 'vue'
import App from './App.vue'
import router from './router'
import { store } from './store'
import './registerServiceWorker'
import ArgonDashboard from './plugins/argon-dashboard'

Vue.config.productionTip = false


Vue.component('crud-layout', require('./components/CrudLayout.vue').default)

Vue.use(ArgonDashboard)
new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')
