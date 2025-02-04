<template>
  <v-container>
    <v-card>
      <v-card-title>
        <v-icon left>mdi-airplane</v-icon>
        Pedidos de Viagem
        <v-spacer></v-spacer>
        <v-select
          v-model="selectedStatus"
          :items="statusOptions"
          label="Filtrar por Status"
          dense
          outlined
          @change="fetchTravelOrders"
        ></v-select>
      </v-card-title>

      <v-card-text>
        <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
        <v-data-table
          v-else
          :headers="headers"
          :items="travelOrders"
          class="elevation-1"
          dense
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
              <td>
                <v-chip
                  :color="getStatusColor(item.status)"
                  dark
                  small
                  outlined
                  class="status-chip"
                  @click="openStatusModal(item)"
                >
                  {{ getStatusText(item.status) }}
                </v-chip>
              </td>
            </tr>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <v-dialog v-model="statusModal" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Alterar Status</span>
        </v-card-title>
        <v-card-text>
          <v-select
            v-model="newStatus"
            :items="filteredStatusOptions"
            label="Selecione o novo status"
            outlined
            dense
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue" @click="updateStatus" :disabled="!newStatus">Confirmar</v-btn>
          <v-btn color="gray" @click="statusModal = false">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TravelOrdersDashboard',
  data() {
    return {
      travelOrders: [],
      selectedStatus: '',
      loading: false,
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Solicitante', value: 'requester_name' },
        { text: 'Destino', value: 'destination' },
        { text: 'Partida', value: 'departure_date' },
        { text: 'Retorno', value: 'return_date' },
        { text: 'Status', value: 'status' },
      ],
      statusOptions: [
        { text: 'Todos', value: '' },
        { text: 'Solicitado', value: 'requested' },
        { text: 'Aprovado', value: 'approved' },
        { text: 'Cancelado', value: 'canceled' },
      ],
      statusModal: false,
      newStatus: null,
      currentOrder: null,
    };
  },
  computed: {
    filteredStatusOptions() {
      if (!this.currentOrder) return this.statusOptions.filter(status => status.value !== '');

      return this.currentOrder.status === 'requested'
        ? this.statusOptions.filter(status => status.value !== '') // Remove "Todos"
        : this.statusOptions.filter(status => status.value !== 'requested' && status.value !== '');
    }
  },
  methods: {
    async fetchTravelOrders() {
      this.loading = true;
      try {
        const token = this.$store.state.auth.token;
        const params = this.selectedStatus ? { status: this.selectedStatus } : {};
        const response = await axios.get(`${process.env.VUE_APP_API_URL}/api/travel-orders`, {
          params,
          headers: { Authorization: `Bearer ${token}` },
        });
        this.travelOrders = response.data;
      } catch (error) {
        this.handleRequestError(error, 'Erro ao buscar pedidos de viagem');
      } finally {
        this.loading = false;
      }
    },
    async updateStatus() {
      try {
        const token = this.$store.state.auth.token;
        const response = await axios.put(
          `${process.env.VUE_APP_API_URL}/api/travel-orders/${this.currentOrder.id}/status`,
          { status: this.newStatus },
          { headers: { Authorization: `Bearer ${token}` } }
        );

        this.showNotification('success', 'Sucesso!', response.data.message);
        this.fetchTravelOrders();
        this.statusModal = false; // Fecha o modal após sucesso
      } catch (error) {
        this.handleRequestError(error, 'Erro ao atualizar status');
      }
    },
    openStatusModal(order) {
      this.currentOrder = order;
      this.newStatus = order.status;
      this.statusModal = true;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('pt-BR');
    },
    getStatusText(status) {
      return this.statusOptions.find(option => option.value === status)?.text || status;
    },
    getStatusColor(status) {
      return {
        requested: 'blue',
        approved: 'green',
        canceled: 'red',
      }[status] || 'gray';
    },
    showNotification(type, title, message) {
      this.$notify({
        group: 'notification',
        type,
        title,
        text: message,
        classes: 'notification-custom',
      });
    },
    handleRequestError(error, defaultMessage) {
      const message = error.response?.data?.message || defaultMessage;
      this.showNotification('error', 'Erro', message);
      if (error.response?.status === 401) {
        this.$router.push('/login');
      }
    },
  },
  mounted() {
    this.fetchTravelOrders();
  },
};
</script>

<style scoped>
.status-chip {
  border-radius: 4px;
  cursor: pointer;
}
</style>
