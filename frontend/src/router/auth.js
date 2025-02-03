import store from '../store';  

export function isAuthenticated(to, from, next) {
  if (!store.state.token) {
    next('/login');  // Redireciona para o login se o token não estiver presente
  } else {
    next();  // Permite o acesso se o token estiver presente
  }
}
