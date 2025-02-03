<template>
  <div>
    <!-- Cabeçalho com logo -->
    <header class="header">
      <div class="logo">Pedido Viagem</div>
    </header>

    <!-- Botões de navegação -->
    <nav class="navbar" v-if="isNotLoginRoute">
      <router-link to="/dashboard" class="nav-btn">Dashboard</router-link>
      <router-link to="/request-travel" class="nav-btn">Solicitar Viagem</router-link>
      <router-link to="/travel-list" class="nav-btn">Consultar solicitações</router-link>
      <button @click="logout" class="nav-btn">Sair</button>
    </nav>

    <!-- Conteúdo principal -->
    <router-view></router-view>
  </div>
</template>

<script>


export default {
  computed: {
    // Verifica se a rota ativa não é a de login
    isNotLoginRoute() {
      return this.$route.path !== '/login';  // Verifica se o nome da rota atual é diferente de 'Login'
    }
  },
  methods: {
    logout() {
      // Implementação para logout
      this.$store.dispatch('logout');
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
/* Fontes Globais */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Roboto', sans-serif;
}

/* Cabeçalho */
.header {
  background-color: #0066b3;  /* Azul semelhante ao usado no site */
  color: white;
  padding: 20px 30px;
  text-align: left;
  font-size: 26px;
  font-weight: 700;
  font-family: 'Montserrat', sans-serif;  /* Fonte para o logo */
}

.logo {
  font-size: 28px;
}

/* Navegação */
.navbar {
  background-color: #f4f4f4;  /* Cinza claro */
  padding: 16px 0;
  display: flex;
  justify-content: space-around;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border-top: 4px solid #0066b3; /* Linha superior para dar mais destaque */
}

.nav-btn {
  padding: 12px 25px;
  background-color: #0066b3;  /* Azul do botão */
  color: white;
  border: none;
  border-radius: 25px;  /* Botões com borda arredondada */
  text-decoration: none;
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.2s ease;
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
}

.nav-btn:hover {
  background-color: #005bb5;  /* Cor mais escura para o hover */
  cursor: pointer;
  transform: scale(1.05);  /* Leve aumento de tamanho no hover */
}

/* Responsividade - Ajustes para telas menores */
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    align-items: center;
  }

  .nav-btn {
    width: 90%;  /* Botões com largura adaptada em dispositivos móveis */
    margin-bottom: 15px;
  }
}

</style>
