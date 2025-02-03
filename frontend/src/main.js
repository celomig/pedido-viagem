import Vue from 'vue';
import App from './App.vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';  // Verifique se o Vuex foi importado
import Login from './components/auth/LoginForm.vue';
import Dashboard from './components/TravelOrders/Dashboard.vue';
import RequestTravel from './components/TravelOrders/RequestTravel.vue';
import TravelOrdersList from './components/TravelOrders/TravelOrdersList.vue';
import store from './store';  // Verifique se você está importando o store corretamente

Vue.config.productionTip = false;

// Ativar Vue Router e Vuex
Vue.use(VueRouter);
Vue.use(Vuex);

// Definir as rotas
const routes = [
  { path: '/login', component: Login, meta: { title: 'Login' } },
  { path: '/dashboard', component: Dashboard, meta: { title: 'Dashboard' } },
  { path: '/request-travel', component: RequestTravel, meta: { title: 'Cadastrar Pedido de Viagem' } },
  { path: '/travel-list', component: TravelOrdersList, meta: { title: 'Lista de Pedidos de Viagem' } },
  { path: '/travel-orders/edit/:id', component: RequestTravel, meta: { title: 'Editar Pedido de Viagem' } },
];


// Criar a instância do roteador
const router = new VueRouter({
  mode: 'history', // Garante URLs amigáveis sem #
  routes
});

router.afterEach((to) => {
  // Define o título da página com base na rota
  document.title = 'Pedido Viagem - ' + to.meta.title || 'Pedido Viagem'; 
});

// Criar e montar a aplicação Vue
new Vue({
  router,
  store,  // Certifique-se de que o store está sendo passado aqui
  render: h => h(App)
}).$mount('#app');
