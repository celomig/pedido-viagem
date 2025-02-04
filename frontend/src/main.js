import Vue from 'vue';
import App from './App.vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Login from './components/auth/LoginForm.vue';
import Dashboard from './components/TravelOrders/Dashboard.vue';
import RequestTravel from './components/TravelOrders/RequestTravel.vue';
import TravelOrdersList from './components/TravelOrders/TravelOrdersList.vue';
import store from './store';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import VueNotification from 'vue-notification';

Vue.use(VueNotification);

Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(Vuex);

const vuetify = new Vuetify(); 

const routes = [
  { path: '/login', component: Login, meta: { title: 'Login' } },
  { path: '/dashboard', component: Dashboard, meta: { title: 'Dashboard' } },
  { path: '/request-travel', component: RequestTravel, meta: { title: 'Cadastrar Pedido de Viagem' } },
  { path: '/travel-list', component: TravelOrdersList, meta: { title: 'Lista de Pedidos de Viagem' } },
  { path: '/travel-orders/edit/:id', component: RequestTravel, meta: { title: 'Editar Pedido de Viagem' } },
];

const router = new VueRouter({
  mode: 'history',
  routes
});

router.afterEach((to) => {
  document.title = 'Pedido Viagem - ' + (to.meta.title || 'Pedido Viagem'); 
});

new Vue({
  vuetify,  
  router,
  store,
  render: h => h(App)
}).$mount('#app');
