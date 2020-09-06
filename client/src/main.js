import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './registerServiceWorker'
import ArgonDashboard from './plugins/argon-dashboard'
import Axios from 'axios'
import './plugins/helper'

Vue.use(ArgonDashboard)

//setting base url for axios
Axios.defaults.baseURL = process.env.VUE_APP_API_URL
const token = localStorage.getItem('access_token')
Axios.defaults.headers.common['Authorization'] = `Bearer ${ token }`

Vue.filter('currency', (money) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(money)
})

Vue.filter('capitalize', val => val.toString().split(' ').map(str => str.charAt(0).toUpperCase() + str.slice(1)).join(' '))

Vue.config.productionTip = false

new Vue({
  store,
  axios: Axios,
  router,
  render: h => h(App)
}).$mount('#app')
