import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.config.productionTip = false

Vue.use(VueRouter)

import Dashboard from './components/Dashboard.vue';
import Calendrier from './components/Calendrier.vue';
import RendezVousForm from './components/RendezVousForm.vue';

const routes = [
  { path: '/calendrier', component: Calendrier },
  { path: '/ajouter', component: RendezVousForm },
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  render: h => h(Dashboard)
}).$mount('#app')