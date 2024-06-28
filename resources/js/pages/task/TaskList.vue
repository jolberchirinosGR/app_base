<template>
    <div class="block space-y-4 md:flex md:space-y-0 md:space-x-4 md:rtl:space-x-reverse" style="margin-bottom: 2%;">
        <h1 class="text-gray dark:text-white text-xl font-bold">
            <font-awesome-icon :icon="['fas', 'list-check']"/>
            Listado de tareas
        </h1>

        <fwb-button class="mr-2" gradient="green" @click="createModalTask()">
          <font-awesome-icon :icon="['fas', 'plus']"/>
          Nueva
        </fwb-button>

        <fwb-dropdown text="Paginación">
          <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200">
            <li>
              <div class="flex items-center">
                  <input type="radio" v-model="paginationNumber" value="10">
                  <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">10</label>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                  <input type="radio" v-model="paginationNumber" value="25">
                  <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">25</label>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                  <input type="radio" v-model="paginationNumber" value="50">
                  <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">50</label>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                  <input type="radio" v-model="paginationNumber" value="100">
                  <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">100</label>
              </div>
            </li>
          </ul>
        </fwb-dropdown>

        <fwb-input #prefix v-model="inputSearch" placeholder="Buscador nombre o correo">
          <font-awesome-icon :icon="['fas', 'search']"/>
        </fwb-input>
    </div>

    <fwb-table>
      <fwb-table-head>
        <fwb-table-head-cell>Tarea</fwb-table-head-cell>
        <fwb-table-head-cell>Descripción</fwb-table-head-cell>
        <fwb-table-head-cell>Fecha</fwb-table-head-cell>
        <fwb-table-head-cell>Usuarios</fwb-table-head-cell>
        <fwb-table-head-cell>Estado <font-awesome-icon color="text-gray-900 dark:text-white" :icon="['fas', 'circle-info']" @click="showDeleteConfirmation"/></fwb-table-head-cell>
        <fwb-table-head-cell>Acciones</fwb-table-head-cell>
      </fwb-table-head>

      <fwb-table-body>
        <TaskListItem v-for="(task, index) in tasks.data"
          :key="task.id"
          :task=task
          @open-update-task="updateModalTask"
          @open-delete-task="deleteModalTask"
        />
      </fwb-table-body>
    </fwb-table>

    <nav class="w-full flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
      <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
        Viendo 
        <span class="font-semibold text-gray-900 dark:text-white">
          {{ tasks.from }}
          -
          {{ tasks.to }}
        </span> 
        de 
        <span class="font-semibold text-gray-900 dark:text-white">
          {{ tasks.total }}
        </span>
      </span>
      <fwb-pagination v-model="tasks.current_page" :total-pages="tasks.last_page" @page-changed="getTasks"  previous-label="<<<" next-label=">>>"></fwb-pagination>
    </nav>

    <!-- Modal -->
    <task-modals ref="taskModals"
        @reload-table="reloadTable"
    />
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import TaskListItem from './TaskListItem.vue';
import TaskModals from './TaskModals.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useSweetAlert }  from '../../stores/Sweet.js';

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
  FwbPagination,
  FwbSelect,
  FwbDropdown,
  FwbInput,
} from 'flowbite-vue'

export default {
  components: {
    TaskListItem,
    TaskModals,
    FwbA,
    FwbTable,
    FwbTableBody,
    FwbTableCell,
    FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow,
    FwbButton,
    FwbPagination,
    FwbSelect,
    FwbDropdown,
    FwbInput,
  },
  data() {
    return {
      //Objeto para la edicion
      task: {
        id: '',
        name: '',
        lastname: '',
        dni: '',
        email: '',
        password: '',
        confirm_password: '',
        id_role: '',
      },
      //Modales y titulos
      id: 0,
      update: false,
      //Variables para comprobar los resultados y el total de los datos
      roles: [],
      tasks: [],
      //Filtros para el listado
      inputSearch: null,
      roleSearch: null,
      dateSearch: null,
      //Generales
      paginationNumber: 10,
      paginationSelect: [
        { value: '10', name: '10'},
        { value: '25', name: '25'},
        { value: '50', name: '50'},
        { value: '100', name: '100'},
      ],
      orderByColumn: '',
      orderByType: 'none', // none, asc, desc
    };
  },
  methods: {
    //Obtener todas los Usuarios
    getTasks(page = 1) {
        axios.get(`/web/tasks?page=${page}`, {
            params: {
                search: this.inputSearch,
                role: this.roleSearch,
                //Generales
                pagination: this.paginationNumber,
                order: this.orderByType,
                column: this.orderByColumn,
            },
        }).then((response) => {
            this.tasks = response.data;
        });
    },

    //Obetener ordenación
      sortBy(column) {
        if (this.orderByColumn === column) {
            if (this.orderByType === 'none') {
                this.orderByType = 'asc';
            } else if (this.orderByType === 'asc') {
                this.orderByType = 'desc';
            } else {
                this.orderByType = 'none';
                this.orderByColumn = '';
            }
        } else {
            this.orderByColumn = column;
            this.orderByType = 'asc';
        }
    },

    // Método para abrir el modal de creación
      createModalTask() {
        this.$refs.taskModals.openFormModal(null);
      },

    // Método para abrir el modal de creación
      updateModalTask(data) {
        this.$refs.taskModals.openFormModal(data);
      },

    // Método para abrir el modal de creación
      deleteModalTask(data) {
        this.$refs.taskModals.openDeleteModal(data);
      },

    //Funcion para recargar la tabla
      reloadTable(){
        this.getTasks();
      },

    //vaciar filtro de fecha
      dateSearchNull(){
        this.dateSearch = null;
      },

      //activar 
      showDeleteConfirmation() {
        const swal = useSweetAlert();
            
        swal.fire({
          title: "Estados de las tareas",
          text: "Explicar los estados aqui",
          icon: "info"
        });
      },
  },
  watch: {
    inputSearch: debounce(function (newVal) {
      this.getTasks();
    }, 300),
    paginationNumber: debounce(function () {
      this.getTasks();
    }, 300),
    dateSearch: debounce(function () {
      this.getTasks();
    }, 300),
    roleSearch: debounce(function () {
      this.getTasks();
    }, 300),
    orderByType: debounce(function () {
      this.getTasks();
    }, 300),
  },
  created() {
    this.getTasks();
  },
  mounted() {
    initFlowbite();
  }
};
</script>
