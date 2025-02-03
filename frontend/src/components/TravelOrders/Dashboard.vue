<template>
  <div class="dashboard">
    <h2>Pedidos de Viagem</h2>

    <!-- Filtro por status -->
    <label for="status">Filtrar por Status:</label>
    <select v-model="selectedStatus" @change="fetchTravelOrders">
      <option value="">Todos</option>
      <option value="requested">Solicitado</option>
      <option value="approved">Aprovado</option>
      <option value="canceled">Cancelado</option>
    </select>

    <!-- Loading Spinner -->
    <div v-if="loading" class="loading-spinner">
      Carregando...
      <div class="spinner"></div>
    </div>

    <!-- Tabela de Pedidos -->
    <table v-if="!loading">
      <thead>
        <tr>
          <th>ID</th>
          <th>Solicitante</th>
          <th>Destino</th>
          <th>Data de Partida</th>
          <th>Data de Retorno</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in travelOrders" :key="order.id">
          <td>{{ order.id }}</td>
          <td>{{ order.requester_name }}</td>
          <td>{{ order.destination }}</td>
          <td>{{ formatDate(order.departure_date) }}</td>
          <td>{{ formatDate(order.return_date) }}</td>
          <td>{{ getStatusText(order.status) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TravelOrdersDashboard',
  data() {
    return {
      travelOrders: [],
      selectedStatus: '', // Default "Todos" selection (empty string)
      loading: false,
    };
  },
  methods: {
    // Método para buscar os pedidos de viagem
    async fetchTravelOrders() {
      this.loading = true;
      try {
        const token = this.$store.state.auth.token;
        
        // Cria o objeto de parâmetros sem o status vazio
        const params = {};
        if (this.selectedStatus) {
          params.status = this.selectedStatus;
        }

        const response = await axios.get(`${process.env.VUE_APP_API_URL}/api/travel-orders`, {
          params,
          headers: { Authorization: `Bearer ${token}` },
        });
        this.travelOrders = response.data;
      } catch (error) {
        console.error('Erro ao buscar pedidos de viagem:', error);
        if (error.response && error.response.status === 401) {
          this.$router.push('/login');
        }
      } finally {
        this.loading = false;
      }
    },
    // Método para traduzir o status
    getStatusText(status) {
      const statusMap = {
        requested: 'Solicitado',
        approved: 'Aprovado',
        canceled: 'Cancelado',
      };
      return statusMap[status] || status;
    },
    formatDate(date) {
      const dateObject = new Date(date);
      
      // Ajuste para garantir que a data não tenha problemas com fusos horários
      const day = String(dateObject.getUTCDate()).padStart(2, '0');
      const month = String(dateObject.getUTCMonth() + 1).padStart(2, '0');
      const year = dateObject.getUTCFullYear();

      return `${day}/${month}/${year}`;
    }
  },
  mounted() {
    this.fetchTravelOrders();
  },
};
</script>

<style scoped>
.dashboard {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #f9f9f9;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}
th {
  background-color: #007bff;
  color: white;
}

.loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 18px;
  margin-top: 20px;
}

.spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
