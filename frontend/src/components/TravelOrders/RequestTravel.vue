<template>
  <div class="request-travel-container">
    <h2>{{ isEdit ? 'Editar Pedido de Viagem' : 'Cadastrar Pedido de Viagem' }}</h2>

    <!-- Exibe o spinner durante o carregamento -->
    <div v-if="isLoading" class="loading-spinner">
      <div class="spinner"></div>
    </div>

    <form v-if="!isSubmitted && !isLoading" @submit.prevent="handleSubmit">
      <div>
        <label for="requester_name">Nome do Solicitante</label>
        <input 
          type="text" 
          id="requester_name" 
          v-model="requester_name" 
          placeholder="Digite o nome do solicitante" 
          required
        />
      </div>

      <div>
        <label for="destination">Destino</label>
        <input 
          type="text" 
          id="destination" 
          v-model="destination" 
          placeholder="Digite o destino da viagem" 
          required
        />
      </div>

      <div>
        <label for="departure_date">Data de Partida</label>
        <input 
          type="date" 
          id="departure_date" 
          v-model="departure_date" 
          required
        />
      </div>

      <div>
        <label for="return_date">Data de Retorno</label>
        <input 
          type="date" 
          id="return_date" 
          v-model="return_date" 
          required
        />
      </div>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <button type="submit" :disabled="isLoading">
        {{ isLoading ? 'Carregando...' : isEdit ? 'Atualizar Pedido' : 'Cadastrar Pedido' }}
      </button>
    </form>

    <!-- Exibe a mensagem de sucesso quando o pedido for cadastrado -->
    <div v-if="isSubmitted" class="success-message">
      <p>{{ successMessage }}</p>
    </div>
  </div>
</template>


<script>
import axios from 'axios';
import { mapState } from 'vuex';

export default {
  name: 'TravelOrdersRequestTravel',
  data() {
    return {
      destination: '',
      departure_date: '',
      return_date: '',
      requester_name: '',
      error: null,
      isLoading: false,
      successMessage: '',
      isSubmitted: false,
    };
  },

  computed: {
    ...mapState({
      token: state => state.auth.token,
    })
  },
  methods: {
    async fetchTravelOrder(id) {
      this.isLoading = true;
      try {
        const response = await axios.get(`${process.env.VUE_APP_API_URL}/api/travel-orders/${id}`, {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });

        if (response.status === 200) {
          const { requester_name, destination, departure_date, return_date } = response.data;
          this.requester_name = requester_name;
          this.destination = destination;
          this.departure_date = departure_date;
          this.return_date = return_date;
        }
      } catch (error) {
        console.error('Erro ao carregar os dados', error);
        if (error.response) {
          this.error = error.response.data.message || 'Erro desconhecido';
        } else {
          this.error = 'Erro de rede';
        }
      } finally {
        this.isLoading = false;
      }
    },

    async handleSubmit() {
      this.error = null;
      this.isLoading = true;

      // Validação da data
      if (new Date(this.return_date) < new Date(this.departure_date)) {
        this.error = 'A data de retorno não pode ser anterior à data de partida';
        this.isLoading = false;
        return;
      }

      const data = {
        requester_name: this.requester_name,
        destination: this.destination,
        departure_date: this.departure_date,
        return_date: this.return_date,
      };

      try {
        const response = await axios.patch(`${process.env.VUE_APP_API_URL}/api/travel-orders/${this.$route.params.id}`, data, {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });

        if (response.status === 200) {
          this.successMessage = response.data.message || "Pedido de viagem atualizado com sucesso!";
          this.isSubmitted = true;

          setTimeout(() => {
            this.$router.push('/travel-list');
          }, 3000);
        }
      } catch (error) {
        if (error.response) {
          this.error = error.response.data.message || 'Erro desconhecido';
        } else {
          this.error = 'Erro de rede';
        }
      } finally {
        this.isLoading = false;
      }
    }
  },

  created() {
    const { id } = this.$route.params;
    if (id) {
      this.fetchTravelOrder(id);
    }
  }
};
</script>



<style scoped>
.request-travel-container {
  max-width: 500px;
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

.loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 150px;
  text-align: center;
}

.spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0066b3;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.success-message {
  background-color: #d4edda; 
  border: 1px solid #c3e6cb; 
  color: #155724; 
  padding: 15px;
  border-radius: 5px; 
  font-size: 16px;
  font-weight: bold;
  margin: 20px 0;
  text-align: center;
  box-shadow: 0 0 10px rgba(0, 128, 0, 0.1); 
}

.error-message {
  background-color: #f8d7da; 
  border: 1px solid #f5c6cb; 
  color: #721c24; 
  padding: 15px;
  border-radius: 5px; 
  font-size: 16px;
  font-weight: bold;
  margin: 20px 0;
  text-align: center;
  box-shadow: 0 0 10px rgba(255, 0, 0, 0.1); 
}
</style>
