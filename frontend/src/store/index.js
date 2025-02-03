import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
      auth: {
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,  // Token armazenado no localStorage
      },
    },
    mutations: {
      setUser(state, user) {
        state.auth.user = user;  // Armazena o usuário logado no estado
      },
      setToken(state, token) {
        state.auth.token = token;  // Armazena o token no estado
        localStorage.setItem('token', token);  // Armazena o token no localStorage
      },
      clearAuth(state) {
        state.auth.user = null;
        state.auth.token = null;
        localStorage.removeItem('token');  // Remove o token do localStorage
      },
      login(state, { user, token }) {
        state.user = user;
        state.token = token;
        localStorage.setItem('user', JSON.stringify(user));  // Salva o usuário no localStorage
        localStorage.setItem('token', token);  // Salva o token no localStorage
      },
      logout(state) {
        state.user = null;
        state.token = null;
        localStorage.removeItem('user');  // Remove o usuário do localStorage
        localStorage.removeItem('token');  // Remove o token do localStorage
      }
    },
    actions: {
      login({ commit }, { user, token }) {
        commit("setUser", user);
        commit('setToken', token);
      },
      logout({ commit }) {
        commit('clearAuth');
      },
    }
  });
  
