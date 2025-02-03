<template>
  <div class="login-container">
    <h2>Login</h2>
    
    <form @submit.prevent="handleLogin">
      <div>
        <label for="email">Email</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          placeholder="Digite seu email" 
          required 
        />
      </div>

      <div>
        <label for="password">Senha</label>
        <input 
          type="password" 
          id="password" 
          v-model="password" 
          placeholder="Digite sua senha" 
          required 
        />
      </div>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <button type="submit" :disabled="isLoading">
        {{ isLoading ? 'Carregando...' : 'Entrar' }}
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null,
      isLoading: false
    };
  },
  computed: {
    ...mapState({
      user: state => state.auth.user
    })
  },
  methods: {
    async handleLogin() {
      this.error = null;
      this.isLoading = true;

      try {
        // console.log('Dados enviados para o backend:', {
        //   email: this.email,
        //   password: this.password
        // });

        const response = await axios.post(`${process.env.VUE_APP_API_URL}/api/login`, {
          email: this.email,
          password: this.password
        });

        // console.log('Resposta da API:', response);  // Adicionei o log aqui

        // Verifica se o status da resposta é 200 (sucesso)
        if (response.status === 200 && response.data.access_token) {
          const token = response.data.access_token;
          const user = response.data.user;

          // Atualiza o Vuex com o token e o usuário
          this.$store.dispatch('login', { user, token });
          
          // Redireciona para a página inicial ou para a rota protegida
          this.$router.push('/dashboard');
        } else {
          throw new Error('Erro de autenticação');
        }

      } catch (error) {
        // console.log('Erro de login:', error);  // Adicionei o log aqui

        // Trata o erro de rede ou resposta inesperada
        if (error.response) {
          this.error = error.response.data.message || 'Erro desconhecido na resposta';
        } else {
          this.error = 'Erro de rede';
        }
      } finally {
        this.isLoading = false;
      }
    }
  }

};
</script>


<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
}

h2 {
  text-align: center;
}

form div {
  margin-bottom: 15px;
}

input {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #0066b3;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:disabled {
  background-color: #ccc;
}

.error-message {
  color: red;
  font-size: 14px;
  text-align: center;
}
</style>
