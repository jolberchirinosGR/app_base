<template>
  <fwb-table-row>
    <fwb-table-cell>
      {{ taskData.name }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ taskData.description }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{  getDate(taskData.start_date) }}
    </fwb-table-cell>

    <!-- <fwb-table-cell>
      {{  getDate(taskData.end_date) }}
    </fwb-table-cell> -->

    <fwb-table-cell>
      <div class="flex flex-wrap gap-1">
        <fwb-badge  class="dark:text-white" size="sm" type="dark" v-for="(user, index) in taskData.users" :key="index">
          {{ user.name }}
        </fwb-badge>
      </div>
    </fwb-table-cell>
    
    <fwb-table-cell class="text-center">
      <strong>
        <font-awesome-icon :class="getIconColor(taskData.status)" :icon="getIcon(taskData.status)" size="lg"/>
        <br>
        {{getStatusName(taskData.status)}}
      </strong>
    </fwb-table-cell>

    <td class="px-6 py-4">
      <fwb-button class="mr-2" gradient="blue" @click="editModalTask(taskData)">
        <font-awesome-icon :icon="['fas', 'edit']"/>
        Detalles
      </fwb-button>

      <fwb-button class="mr-2" gradient="red" @click="deleteModalTask(taskData)">
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
  FwbBadge,
} from 'flowbite-vue'

  export default {
    emits: ['open-update-task', 'open-delete-task'], //Eventos que se generan en este componente
    
    components: {
      FwbA,
      FwbTable,
      FwbTableBody,
      FwbTableCell,
      FwbTableHead,
      FwbTableHeadCell,
      FwbTableRow,
      FwbButton,
      FwbBadge,
    },
    props: {
      task: Object,
    },
    data() {
      return {
        taskData: this.task,
        rolesAll: [],
        statusAll: ['', '', '', ''],
      };
    },
    created() {
    },
    watch: {
      task(newTask) {
        this.taskData = newTask;
      },
    },
    methods: {
      // Método para abrir el modal de edición
        editModalTask(task) {
          this.$emit('open-update-task', task);
        },

      // Método para abrir el modal de eliminación
        deleteModalTask(task) {
          this.$emit('open-delete-task', task);
        },

      //Obtener icono dependiendo el estatus
        getIcon(status) {
          switch (status) {
            case 1:
              return ['fas', 'sync-alt'];
              break;
          
            case 2:
              return ['fas', 'ban'];
              break;
          
            case 3:
              return ['fas', 'check-double'];
              break;
          
            default:
              return ['fas', 'hourglass'];
              break;
          }
        },

      //Obtener icono dependiendo el estatus
        getStatusName(status) {
          switch (status) {
            case 1:
              return 'En curso';
              break;
          
            case 2:
              return 'Cancelada';
              break;
          
            case 3:
              return 'Finalizado';
              break;
          
            default:
              return 'Por comenzar';
              break;
          }
        },

      //Obtener icono dependiendo el estatus
        getIconColor(status) {
          switch (status) {
            case 1:
              return 'text-blue-700 dark:text-blue-400'; // Modo claro y oscuro
            case 2:
              return 'text-red-700 dark:text-red-400'; // Modo claro y oscuro
            case 3:
              return 'text-green-700 dark:text-green-400'; // Modo claro y oscuro
            default:
              return 'text-gray-700 dark:text-gray-400'; // Modo claro y oscuro
          }
        },   

      //Cambiar el formato de fecha a DD-MM-YY
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