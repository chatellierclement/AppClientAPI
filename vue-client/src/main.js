import Vue from 'vue'
import VueRouter from 'vue-router'
import VueCkeditor from 'vue-ckeditor2';

Vue.config.productionTip = false

Vue.use(VueRouter)
Vue.use(VueCkeditor);

import Dashboard from './components/Dashboard.vue';
import Calendrier from './components/Calendrier.vue';
import Modele from './components/Modele.vue';

const routes = [
  { path: '/calendrier', component: Calendrier },
  { path: '/modele', component: Modele },
]

const router = new VueRouter({
  mode: 'history',
  routes
})

new Vue({
  router,
  render: h => h(Dashboard)
}).$mount('#app')