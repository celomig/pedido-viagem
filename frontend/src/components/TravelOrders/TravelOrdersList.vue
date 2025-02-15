<template>
  <v-container>
    <v-card class="travel-orders-list-container">
      <v-card-title>
        <h2>Lista de Pedidos de Viagem</h2>
      </v-card-title>

      <v-card-text class="filters">
        <v-row>
          <v-col cols="12">
            <h4>Filtrar:</h4>
            <v-divider></v-divider>
          </v-col>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.requester_name"
              label="Solicitante"
              outlined
              dense
            ></v-text-field>
          </v-col>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.destination"
              label="Destino"
              outlined
              dense
            ></v-text-field>
          </v-col>

        </v-row>
        <v-row>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.departure_date_from"
              label="Data de Partida (de)"
              type="date"
              outlined
              dense
            ></v-text-field>
          </v-col>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.departure_date_to"
              label="Data de Partida (até)"
              type="date"
              outlined
              dense
            ></v-text-field>
          </v-col>

        </v-row>
        <v-row>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.return_date_from"
              label="Data de Retorno (de)"
              type="date"
              outlined
              dense
            ></v-text-field>
          </v-col>

          <v-col cols="12" sm="6" md="4">
            <v-text-field
              v-model="filters.return_date_to"
              label="Data de Retorno (até)"
              type="date"
              outlined
              dense
            ></v-text-field>
          </v-col>

          <v-col cols="12"  sm="6" md="4">
            <v-btn @click="clearFilters" color="secondary">Limpar Filtros</v-btn>
          </v-col>
        </v-row>

        <v-alert v-if="isLoading" type="info">Carregando...</v-alert>
        <v-alert v-if="error" type="error">{{ error }}</v-alert>
        <v-alert v-if="successMessage" type="success">{{ successMessage }}</v-alert>

        <v-data-table
          v-if="travelOrders.length > 0"
          :headers="headers"
          :items="filteredTravelOrders"
          :loading="isLoading"
          class="elevation-1"
          hide-rows-per-page
          :items-per-page="100"
          hide-default-footer
        >
          <template v-slot:item="{ item }">
            <tr>
              <td>{{ item.id }}</td>
              <td>{{ item.requester_name }}</td>
              <td>{{ item.destination }}</td>
              <td>{{ formatDate(item.departure_date) }}</td>
              <td>{{ formatDate(item.return_date) }}</td>
              <td>{{ item.created_by.name }}</td>
              <td>{{ getStatusText(item.status) }}</td>
              <td>
                <v-btn small color="primary" @click="editOrder(item.id)">Editar</v-btn>
                <v-btn small color="error" @click="deleteOrder(item.id)">Remover</v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>


        <v-alert v-if="travelOrders.length === 0" type="info">Nenhum pedido de viagem encontrado.</v-alert>
      </v-card-text>
    </v-card>
  </v-container>
</template>



<script>
import axios from 'axios';
import { mapState } from 'vuex';

export default {
  name: 'TravelOrdersList',
  data() {
    return {
      travelOrders: [], // Lista completa de pedidos de viagem
      filters: {
        requester_name: '',
        destination: '',
        departure_date_from: '',
        departure_date_to: '',
        return_date_from: '',
        return_date_to: '',
      },
      isLoading: false,
      error: null, // Para exibir mensagens de erro
      successMessage: null, // Para exibir mensagens de sucesso
      headers: [
        { text: 'Id', value: 'id' },
        { text: 'Solicitante', value: 'requester_name' },
        { text: 'Destino', value: 'destination' },
        { text: 'Data de Partida', value: 'departure_date' },
        { text: 'Data de Retorno', value: 'return_date' },
        { text: 'Cadastrado por', value: 'created_by.name' },
        { text: 'Status', value: 'status' },
        { text: 'Ações', value: 'actions', sortable: false },
      ],
    };
  },
  computed: {
    // Filtro da lista de pedidos de viagem
    filteredTravelOrders() {
      return this.travelOrders.filter(order => {
        const matchesRequesterName = order.requester_name
          .toLowerCase()
          .includes(this.filters.requester_name.toLowerCase());
        const matchesDestination = order.destination
          .toLowerCase()
          .includes(this.filters.destination.toLowerCase());

        // Filtro de datas
        const matchesDepartureDate = this.filters.departure_date_from
          ? new Date(order.departure_date) >= new Date(this.filters.departure_date_from)
          : true;
        const matchesDepartureDateTo = this.filters.departure_date_to
          ? new Date(order.departure_date) <= new Date(this.filters.departure_date_to)
          : true;
        const matchesReturnDateFrom = this.filters.return_date_from
          ? new Date(order.return_date) >= new Date(this.filters.return_date_from)
          : true;
        const matchesReturnDateTo = this.filters.return_date_to
          ? new Date(order.return_date) <= new Date(this.filters.return_date_to)
          : true;

        return (
          matchesRequesterName &&
          matchesDestination &&
          matchesDepartureDate &&
          matchesDepartureDateTo &&
          matchesReturnDateFrom &&
          matchesReturnDateTo
        );
      });
    },
    ...mapState({
      token: state => state.auth.token,
    }),
  },
  methods: {
    // Aplica os filtros e recarrega os pedidos de viagem
    applyFilters() {
      this.isLoading = true;
      const filterParams = {};

      // Adiciona os filtros ao objeto filterParams, mas só se estiverem preenchidos
      if (this.filters.requester_name) {
        filterParams.requester_name = this.filters.requester_name;
      }
      if (this.filters.destination) {
        filterParams.destination = this.filters.destination;
      }
      if (this.filters.departure_date_from) {
        filterParams.departure_date_from = this.filters.departure_date_from;
      }
      if (this.filters.departure_date_to) {
        filterParams.departure_date_to = this.filters.departure_date_to;
      }
      if (this.filters.return_date_from) {
        filterParams.return_date_from = this.filters.return_date_from;
      }
      if (this.filters.return_date_to) {
        filterParams.return_date_to = this.filters.return_date_to;
      }

      // Recarregar os pedidos com os filtros aplicados
      this.fetchTravelOrders(filterParams);
    },

    // Faz a requisição para buscar os pedidos de viagem com os filtros aplicados
    async fetchTravelOrders(filters = {}) {
      this.isLoading = true;
      try {
        const response = await axios.get(`${process.env.VUE_APP_API_URL}/api/travel-orders`, {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
          params: filters, // Passando os filtros como parâmetros na requisição
        });
        this.travelOrders = response.data; // Atualiza a lista de pedidos de viagem
      } catch (error) {
        console.error('Erro ao carregar os pedidos de viagem', error);
      } finally {
        this.isLoading = false;
      }
    },

    // Formatação das datas para exibição
    formatDate(date) {
      return new Date(date).toLocaleDateString('pt-BR');
    },

    // Editar o pedido de viagem
    editOrder(id) {
      this.$router.push(`/travel-orders/edit/${id}`);
    },

    // Remover o pedido de viagem
    async deleteOrder(id) {
      this.error = null;
      this.isLoading = true;
      this.successMessage = null; // Limpar qualquer mensagem de sucesso anterior

      // Confirmação antes de excluir
      if (!confirm('Tem certeza que deseja remover este pedido de viagem?')) {
        this.isLoading = false;
        return;
      }

      try {
        const response = await axios.delete(`${process.env.VUE_APP_API_URL}/api/travel-orders/${id}`, {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });

        // Se a resposta for bem-sucedida (status 200 ou 204)
        if (response.status === 200 || response.status === 204) {
          this.successMessage = response.data.message || 'Pedido de viagem removido com sucesso!';
          this.isSubmitted = true;

          // Recarregar a lista de pedidos
          this.fetchTravelOrders();
        }
      } catch (error) {
        // Erro ao tentar remover
        if (error.response) {
          this.error = error.response.data.message || 'Erro desconhecido';
        } else {
          this.error = 'Erro de rede';
        }
      } finally {
        // Finaliza o carregamento
        this.isLoading = false;
      }
    },

    getStatusText(status) {
      const statusMap = {
        requested: 'Solicitado',
        approved: 'Aprovado',
        canceled: 'Cancelado',
      };
      return statusMap[status] || status;
    },

    clearFilters() {
      this.filters = {
        requester_name: '',
        destination: '',
        departure_date_from: '',
        departure_date_to: '',
        return_date_from: '',
        return_date_to: '',
      };
      this.fetchTravelOrders(); // Recarrega todos os pedidos de viagem sem filtros
    },
  },
  created() {
    if (!this.token) {
      this.$router.push('/login');
      return;
    }
    
    this.fetchTravelOrders(); // Carregar os pedidos de viagem ao carregar o componente
  },
};
</script>

<style scoped>
.travel-orders-list-container {
  margin: 20px;
}

.filters {
  margin-bottom: 20px;
}

.filters input {
  margin-right: 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  padding: 10px;
  text-align: left;
}

button {
  background-color: #0066b3;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 5px 10px;
  cursor: pointer;
  margin-right: 5px;
}

button:hover {
  background-color: #004a8d;
}

button:disabled {
  background-color: #ccc;
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

span {
  cursor: pointer;
  color: #0066b3;
  text-decoration: underline;
}

span:hover {
  text-decoration: none;
}
</style>