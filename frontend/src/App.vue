<template>
  <v-app>
    <!-- Barra de Aplicação -->
    <v-app-bar color="primary" dark app>
      <v-toolbar-title>Pedido Viagem</v-toolbar-title>
      <v-spacer></v-spacer>
      
      <!-- Exibe a navegação apenas se não estiver na tela de login -->
      <template v-if="isNotLoginRoute">
        <v-btn text to="/dashboard">Dashboard</v-btn>
        <v-btn text to="/request-travel">Solicitar Viagem</v-btn>
        <v-btn text to="/travel-list">Consultar Solicitações</v-btn>
        <v-btn text @click="logout">Sair</v-btn>
      </template>
    </v-app-bar>

    <!-- Menu lateral responsivo para mobile -->
    <v-navigation-drawer v-model="drawer" app temporary>
      <v-list>
        <v-list-item to="/dashboard">
          <v-list-item-icon><v-icon>mdi-view-dashboard</v-icon></v-list-item-icon>
          <v-list-item-content><v-list-item-title>Dashboard</v-list-item-title></v-list-item-content>
        </v-list-item>

        <v-list-item to="/request-travel">
          <v-list-item-icon><v-icon>mdi-airplane</v-icon></v-list-item-icon>
          <v-list-item-content><v-list-item-title>Solicitar Viagem</v-list-item-title></v-list-item-content>
        </v-list-item>

        <v-list-item to="/travel-list">
          <v-list-item-icon><v-icon>mdi-file-document</v-icon></v-list-item-icon>
          <v-list-item-content><v-list-item-title>Consultar Solicitações</v-list-item-title></v-list-item-content>
        </v-list-item>

        <v-list-item @click="logout">
          <v-list-item-icon><v-icon>mdi-logout</v-icon></v-list-item-icon>
          <v-list-item-content><v-list-item-title>Sair</v-list-item-title></v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Ícone de menu para abrir a navegação no mobile -->
    <v-app-bar-nav-icon @click="drawer = !drawer" v-if="isNotLoginRoute"></v-app-bar-nav-icon>

    <!-- Conteúdo principal -->
    <v-main>
      <router-view></router-view>
    </v-main>

    <notifications group="notification"></notifications>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      drawer: false, // Controla o menu lateral no mobile
    };
  },
  computed: {
    isNotLoginRoute() {
      return this.$route.path !== '/login';
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('logout');
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>

</style>
