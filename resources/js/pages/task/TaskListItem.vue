<template>
  <fwb-table-row>
    <fwb-table-cell>
      {{ taskData.name }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ taskData.description }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ taskData.date }}
    </fwb-table-cell>

    <fwb-table-cell>
      <div class="flex flex-wrap gap-1">
        <fwb-badge size="sm" type="dark" v-for="(user, index) in taskData.users" :key="index">
          {{ user.name }}
        </fwb-badge>
      </div>
    </fwb-table-cell>
    
    <fwb-table-cell class="text-center">
      <strong>
        <font-awesome-icon :class="getIconColor(taskData.status)" :icon="getIcon(taskData.status)" size="lg"/>
      </strong>
    </fwb-table-cell>

    <td class="px-6 py-4">
      <fwb-button class="mr-2" gradient="blue" @click="editModalTask(taskData)">
        <font-awesome-icon :icon="['fas', 'edit']"/>
        Editar
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
    },
  };
</script>