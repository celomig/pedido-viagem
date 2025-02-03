import Vue from 'vue';
import Router from 'vue-router';
import { isAuthenticated } from './auth';
import Login from '../components/auth/Login.vue';
import Dashboard from '../components/Dashboard.vue';
import RequestTravel from '../components/TravelOrders/RequestTravel.vue';
import TravelOrdersList from '../components/TravelOrders/TravelOrdersList.vue';
import store from '../store';  // Importando o store para acessar o estado global

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login,
      beforeEnter: (to, from, next) => {
        // Se o usuário estiver autenticado, redireciona para a Dashboard
        if (store.state.auth.token) {  // Verificando se há um token no Vuex
          next('/dashboard');
        } else {
          next();  // Se não estiver logado, continua para a página de login
        }
      }
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      beforeEnter: isAuthenticated,  // Verifica se o usuário está autenticado para acessar
    },
    {
      path: '/request-travel',
      name: 'RequestTravel',
      component: RequestTravel,  
      beforeEnter: isAuthenticated,  // Verifica se o usuário está autenticado para acessar
    },
    {
      path: '/travel-list',
      name: 'TravelOrdersList',
      component: TravelOrdersList,  
      beforeEnter: isAuthenticated,  // Verifica se o usuário está autenticado para acessar
    },   
    {
      path: '/travel-orders/edit/:id',
      name: 'RequestTravel',
      component: RequestTravel, 
      props: true, // Passa os parâmetros da URL como props para o componente
    },
    {
      path: '*',  // Demais rotas
      redirect: '/login',  // Redireciona para o login por padrão
    },
  ],
});
