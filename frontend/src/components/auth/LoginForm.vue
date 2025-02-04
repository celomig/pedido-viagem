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

      <!-- Exibe todos os erros de forma dinâmica -->
      <div v-if="Object.keys(errors).length > 0" class="error-message">
        <ul>
          <li v-for="(messages, field) in errors" :key="field">
            
              <span v-for="(message, index) in messages" :key="index">
                {{ message }}
              </span>
            
          </li>
        </ul>
      </div>

      <div v-if="error && Object.keys(errors).length === 0" class="error-message">
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
      error: null,  // Erro genérico
      errors: {},   // Erros específicos de cada campo
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
      this.errors = {};  // Limpa os erros antes de uma nova tentativa de login
      this.isLoading = true;

      try {
        const response = await axios.post(`${process.env.VUE_APP_API_URL}/api/login`, {
          email: this.email,
          password: this.password
        });

        if (response.status === 200 && response.data.access_token) {
          const token = response.data.access_token;
          const user = response.data.user;

          this.$store.dispatch('login', { user, token });
          
          this.$router.push('/dashboard');
        } else {
          throw new Error('Erro de autenticação');
        }

      } catch (error) {
        if (error.response) {
          // Verifica se há erros de validação específicos para o campo
          if (error.response.data.errors) {
            this.errors = error.response.data.errors;
          } else {
            this.error = error.response.data.message || 'Erro desconhecido na resposta';
          }
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

ul {
  padding-left: 20px;
  list-style-type: none;
}
</style>
