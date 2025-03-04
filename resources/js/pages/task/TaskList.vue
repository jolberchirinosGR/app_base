<template>
  <div class="block space-y-4 md:flex md:space-y-0 md:space-x-4 md:rtl:space-x-reverse items-center" style="margin-bottom: 2%;">
      <h1 class="text-gray dark:text-white text-xl font-bold flex items-center">
          <font-awesome-icon :icon="['fas', 'list-check']" class="mr-2"/>
          Listado de tareas
      </h1>

      <fwb-button gradient="blue" @click="openNewTask()" class="mr-2">
        <font-awesome-icon :icon="['fas', 'plus']"/>
        Nueva tarea
      </fwb-button>
      
      <fwb-button gradient="cyan" @click="changeView()">
        <font-awesome-icon :icon="['fa', 'columns']"/>
        Mi panel de tareas
      </fwb-button>

      <fwb-dropdown text="Paginación" class="mr-2">
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
        
      <fwb-input #prefix v-model="inputSearch" placeholder="Buscador..." class="mr-2">
        <font-awesome-icon :icon="['fas', 'search']"/>
      </fwb-input>
  </div>

  <fwb-table>
    <fwb-table-head>
      <fwb-table-head-cell>Tarea</fwb-table-head-cell>
      <fwb-table-head-cell>Descripción</fwb-table-head-cell>
      <fwb-table-head-cell>Fecha Inicio</fwb-table-head-cell>
      <!-- <fwb-table-head-cell>Fecha Fin</fwb-table-head-cell> -->
      <fwb-table-head-cell>Usuarios</fwb-table-head-cell>
      <fwb-table-head-cell>Estado <font-awesome-icon color="text-gray-900 dark:text-white" :icon="['fas', 'circle-info']" @click="showStatusInfo"/></fwb-table-head-cell>
      <fwb-table-head-cell>Acciones</fwb-table-head-cell>
    </fwb-table-head>

    <fwb-table-body>
      <TaskListItem v-for="(task, index) in tasks.data"
        :key="task.id"
        :task="task"
        @open-update-task="openUpdateTask"
        @open-delete-task="openDeleteTask"
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
    <fwb-pagination v-model="tasks.current_page" :total-pages="tasks.last_page" @page-changed="getTasks" previous-label="<<<" next-label=">>>"></fwb-pagination>
  </nav>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';
import TaskListItem from './TaskListItem.vue';
import { useSweetAlert } from '../../stores/Sweet';

// Flowbite components
import {
  FwbTable,
  FwbTableBody,
  FwbTableHead,
  FwbTableHeadCell,
  FwbButton,
  FwbPagination,
  FwbDropdown,
  FwbInput,
} from 'flowbite-vue';

const tasks = ref([]);
const inputSearch = ref('');
const paginationNumber = ref(10);
const currentPage = ref(1);
const orderByColumn = ref('');
const orderByType = ref('none');

const emit = defineEmits(['change-view', 'open-new-task', 'open-update-task', 'open-delete-task']);

const getTasks = async (page = currentPage.value) => {
  try {
    const response = await axios.get(`/web/tasks?page=${page}`, {
      params: {
        search: inputSearch.value,
        pagination: paginationNumber.value,
        order: orderByType.value,
        column: orderByColumn.value,
      },
    });
    tasks.value = response.data;
  } catch (error) {
    console.error('Error fetching tasks:', error);
  }
};

const sortBy = (column) => {
  if (orderByColumn.value === column) {
    if (orderByType.value === 'none') {
      orderByType.value = 'asc';
    } else if (orderByType.value === 'asc') {
      orderByType.value = 'desc';
    } else {
      orderByType.value = 'none';
      orderByColumn.value = '';
    }
  } else {
    orderByColumn.value = column;
    orderByType.value = 'asc';
  }
};

const changeView = () => {
    emit('change-view');
};

const openNewTask = () => {
    emit('open-new-task');
};

const openUpdateTask = (data) => {
    emit('open-update-task', data);
};

const openDeleteTask = (data) => {
    emit('open-delete-task', data);
};

const reload = () => {
  currentPage.value = 1;
  getTasks(currentPage.value);
};

const showStatusInfo = () => {
  const swal = useSweetAlert();
  swal.fire({
    title: 'Estados de las tareas',
    text: 'Explicar los estados aquí',
    icon: 'info',
  });
};

//Metodos para actualizar, cargar de inicio y exportar a otros elementos 
watch([paginationNumber, inputSearch, orderByType ], debounce(reload), 300);

onMounted(() => {
  getTasks();
});

defineExpose({
  reload,
});
</script>