<template>
  <div class="request-travel-container">
    <v-container>
      <v-row>
        <v-col cols="12" md="8" offset-md="2">
          <v-card>
            <v-card-title>
              <span class="headline">{{ isEdit ? 'Editar Pedido de Viagem' : 'Cadastrar Pedido de Viagem' }}</span>
            </v-card-title>

            <!-- Exibe o spinner durante o carregamento -->
            <v-progress-circular
              v-if="isLoading"
              indeterminate
              color="primary"
              size="64"
              width="6"
              class="loading-spinner"
            ></v-progress-circular>

            <v-card-text>
              <form v-if="!isSubmitted && !isLoading" @submit.prevent="handleSubmit">
                <v-text-field
                  v-model="requester_name"
                  label="Nome do Solicitante"
                  placeholder="Digite o nome do solicitante"
                  required
                  :error-messages="error && !requester_name ? ['Nome do solicitante é obrigatório'] : []"
                ></v-text-field>

                <v-text-field
                  v-model="destination"
                  label="Destino"
                  placeholder="Digite o destino da viagem"
                  required
                  :error-messages="error && !destination ? ['Destino é obrigatório'] : []"
                ></v-text-field>

                <v-dialog v-model="departureDatePicker" max-width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-bind="attrs"
                      v-on="on"
                      v-model="departure_date"
                      label="Data de Partida"
                      readonly
                      required
                      :value="formattedDepartureDate"
                      :error-messages="error && !departure_date ? ['Data de partida é obrigatória'] : []"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="departure_date"
                    @input="departureDatePicker = false"
                    :locale="'pt-br'"
                  ></v-date-picker>
                </v-dialog>

                <v-dialog v-model="returnDatePicker" max-width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-bind="attrs"
                      v-on="on"
                      v-model="return_date"
                      label="Data de Retorno"
                      readonly
                      required
                      :value="formattedReturnDate"
                      :error-messages="error && !return_date ? ['Data de retorno é obrigatória'] : []"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="return_date"
                    @input="returnDatePicker = false"
                    :locale="'pt-br'"
                  ></v-date-picker>
                </v-dialog>

                <v-btn
                  :loading="isLoading"
                  :disabled="isLoading"
                  color="primary"
                  block
                  type="submit"
                >
                  {{ isLoading ? 'Carregando...' : isEdit ? 'Atualizar Pedido' : 'Cadastrar Pedido' }}
                </v-btn>
              </form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';
import moment from 'moment';
import 'moment/locale/pt-br';

moment.locale('pt-br');

export default {
  name: 'TravelOrdersRequestTravel',
  data() {
    return {
      destination: '',
      departure_date: '',
      return_date: '',
      requester_name: '',
      isLoading: false,
      isSubmitted: false,
      departureDatePicker: false,
      returnDatePicker: false,
      error: null,
      isEdit: false,
    };
  },

  computed: {
    ...mapState({
      token: state => state.auth.token,
    }),

    formattedDepartureDate() {
      return this.departure_date ? moment(this.departure_date).format('DD/MM/YYYY') : '';
    },

    formattedReturnDate() {
      return this.return_date ? moment(this.return_date).format('DD/MM/YYYY') : '';
    },
  },

  methods: {
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
        this.handleRequestError(error, 'Erro ao carregar os dados');
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
        this.showNotification('error', 'Erro', this.error);
        return;
      }

      const data = {
        requester_name: this.requester_name,
        destination: this.destination,
        departure_date: this.departure_date,
        return_date: this.return_date,
      };

      try {
        let response;

        if (this.isEdit) {
          // Caso de edição (PATCH)
          response = await axios.patch(`${process.env.VUE_APP_API_URL}/api/travel-orders/${this.$route.params.id}`, data, {
            headers: {
              Authorization: `Bearer ${this.token}`,
            },
          });
        } else {
          // Caso de cadastro (POST)
          response = await axios.post(`${process.env.VUE_APP_API_URL}/api/travel-orders`, data, {
            headers: {
              Authorization: `Bearer ${this.token}`,
            },
          });
        }

        if (response.status === 200 || response.status === 201) {
          const message = this.isEdit ? 'Pedido de viagem atualizado com sucesso!' : 'Pedido de viagem cadastrado com sucesso!';
          this.successMessage = response.data.message || message;
          this.isSubmitted = true;

          // Chamada do notify para sucesso
          this.showNotification('success', 'Sucesso', this.successMessage);

          setTimeout(() => {
            this.$router.push('/travel-list');
          }, 30);
        }
      } catch (error) {
        if (error.response) {
          this.error = error.response.data.message || 'Erro desconhecido';
        } else {
          this.error = 'Erro de rede';
        }

        // Chamada do notify para erro
        this.showNotification('error', 'Erro', this.error);
      } finally {
        this.isLoading = false;
      }
    },

    resetForm() {
      this.requester_name = '';
      this.destination = '';
      this.departure_date = '';
      this.return_date = '';
    }
  },

  watch: {
    '$route.params.id': function (newId) {
      if (newId) {
        // Quando houver um ID na URL, significa que estamos editando
        this.isEdit = true;
        this.fetchTravelOrder(newId);
      } else {
        // Caso contrário, estamos criando
        this.isEdit = false;
        this.resetForm();
      }
    },
  },

  created() {
    // A primeira vez que o componente é carregado, verifique o ID
    const { id } = this.$route.params;

    if (!this.token) {
      this.$router.push('/login');
      return;
    }

    if (id) {
      this.isEdit = true;
      this.fetchTravelOrder(id);
    } else {
      this.isEdit = false;
      this.resetForm();
    }
  }
};
</script>
