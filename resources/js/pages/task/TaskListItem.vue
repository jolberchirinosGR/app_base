<template>
  <fwb-table-row>
    <fwb-table-cell>
      {{ userData.name }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ userData.email }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ getDate(userData.created_at) }}
    </fwb-table-cell>

    <td class="px-6 py-4">
      <fwb-button class="mr-2" gradient="blue" @click="editModalUser(userData)">
        <font-awesome-icon :icon="['fas', 'edit']"/>
        Editar
      </fwb-button>

      <fwb-button class="mr-2" gradient="red" @click="deleteModalUser(userData)">
        <font-awesome-icon :icon="['fas', 'trash']"/>
        Eliminar
      </fwb-button>
    </td>
  </fwb-table-row>

</template>
  
<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

//Elementos del flowbite
import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton,
} from 'flowbite-vue'

  export default {
    emits: ['open-update-user', 'open-delete-user'], //Eventos que se generan en este componente
    
    components: {
      FwbA,
      FwbTable,
      FwbTableBody,
      FwbTableCell,
      FwbTableHead,
      FwbTableHeadCell,
      FwbTableRow,
      FwbButton,
    },
    props: {
      user: Object,
    },
    data() {
      return {
        userData: this.user,
        rolesAll: [],
      };
    },
    created() {
    },
    watch: {
      user(newUser) {
        this.userData = newUser;
      },
    },
    methods: {
      // Método para abrir el modal de edición
        editModalUser(user) {
          this.$emit('open-update-user', user);
        },

      // Método para abrir el modal de eliminación
        deleteModalUser(user) {
          this.$emit('open-delete-user', user);
        },
      //Obtener fecha en formato carbon
        getDate(date) {
          const d = new Date(date);
          const day = String(d.getDate()).padStart(2, '0'); // Ajusta el día a dos dígitos
          const month = String(d.getMonth() + 1).padStart(2, '0'); // Ajusta el mes a dos dígitos
          const year = d.getFullYear();
          return `${day}-${month}-${year}`;
        }
    },
  };
</script>