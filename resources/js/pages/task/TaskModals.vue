<template>
  <fwb-modal v-if="isShowModal" @close="closeFormModal" size="4xl" persistent class="fixed top-0 left-0 right-0 z-50">
    <template #header>
      <div class="flex items-center text-lg text-gray-500 dark:text-white">
        <strong>
          <font-awesome-icon :icon="['fas', 'list-check']"/>
          {{ update ? 'Editar Tarea' : 'Nueva Tarea' }}
        </strong>
      </div>
    </template>

    <template #body>
      <form class="p-4 md:p-5">
        <div class="grid gap-4 mb-4 grid-cols-3">
          <div class="col-span-3">
            <fwb-input v-model="task.name" label="Titulo de la tarea" placeholder="Ingresa el titulo de la tarea" />
          </div>

          <div class="col-span-3">
            <fwb-textarea v-model="task.description" label="Descripción de la tarea" placeholder="Ingresa la descripción detallada" />
          </div>

          <div class="col-span-1">
            <label class="label-form-custom">
              Fecha inicio de la tarea
            </label>
            <flat-pickr v-model="task.start_date" :config="dateConfig" class="input-form-custom"/>
          </div>

          <div class="col-span-1">
            <label class="label-form-custom">
              Hora de ejecución de la tarea
            </label>
            <flat-pickr v-model="task.hour" :config="timeConfig" class="input-form-custom"/>
          </div>

          <div class="col-span-1">
            <label class="label-form-custom">
              ¿Quieres repetir esta tarea?
            </label>
            <fwb-toggle v-model="task.repeat" :label="task.repeat ? 'Si' : 'No'" />
          </div>

          <div class="col-span-3" v-if="task.repeat">
            <label class="label-form-custom">
              Repetir la tarea:
            </label>
            <div class="sm: flex space-x-4">
              <fwb-checkbox @click="setAllDays($event.target.checked)" label="Todos los días"/>
              <fwb-checkbox v-model="task.days['Monday']" label="Lunes"/>
              <fwb-checkbox v-model="task.days['Tuesday']" label="Martes"/>
              <fwb-checkbox v-model="task.days['Wednesday']" label="Miércoles"/>
              <fwb-checkbox v-model="task.days['Thursday']" label="Jueves"/>
              <fwb-checkbox v-model="task.days['Friday']" label="Viernes"/>
              <fwb-checkbox v-model="task.days['Saturday']" label="Sábado"/>
              <fwb-checkbox v-model="task.days['Sunday']" label="Domingo"/>
            </div>
          </div>

          <br>

          <div class="col-span-3" v-if="task.repeat">
            <label class="label-form-custom">
              Selecciona el período que deseas:
            </label>
            <div class="sm: flex">
              <fwb-radio v-model="task.period" label="Proximos 7 Días" value="week"/>
              <fwb-radio v-model="task.period" label="Proximos 15 Días" value="2weeks"/>
              <fwb-radio v-model="task.period" label="Proximos 30 Días" value="month"/>
              <fwb-radio v-model="task.period" label="Todo el año" value="year"/>
            </div>
          </div>

          <br>

          <div class="col-span-3">
            <fwb-input v-model="inputSearch" label="Asigna usuarios a esta tarea" placeholder="Buscar usuarios por nombre" />

            <ul v-show="inputSearch && usersAll.length > 0" class="autocomplete-results input-form-custom">
              <li v-for="result in usersAll" @click="addUser(result)" :key="result.id">
                {{ result.name }} - {{ result.email }}
              </li>
            </ul>
          </div>

          <div class="col-span-1">
            <label class="label-form-custom">
              Usuarios asignados a esta tarea
            </label>
            <fwb-list-group class="border-none">
              <fwb-list-group-item hover v-for="(user, index) in usersAssigned" @click="removeUser(user)" class="input-form-custom" :key="index">
                {{ user.name }}
                <font-awesome-icon :icon="['fas', 'user-times']"/>
              </fwb-list-group-item>
            </fwb-list-group>
          </div>
        </div>
      </form>
    </template>

    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeFormModal" color="alternative">
          <font-awesome-icon :icon="['fas', 'times']"/>
          Cerrar
        </fwb-button>
        <fwb-button v-if="update" @click="updateTask" color="green">
          <font-awesome-icon :icon="['fas', 'edit']"/>
          Modificar
        </fwb-button>
        <fwb-button v-else @click="saveTask" color="blue">
          <font-awesome-icon :icon="['fas', 'save']"/>
          Guardar
        </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';
import flatPickr from 'vue-flatpickr-component';
import { showSuccessMessage, showErrorGroupMessages } from '../../stores/Sweet';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Elementos del flowbite
import {
  FwbModal,
  FwbButton,
  FwbCheckbox,
  FwbInput,
  FwbRadio,
  FwbTextarea,
  FwbToggle,
  FwbListGroup,
  FwbListGroupItem,
} from 'flowbite-vue';

const task = ref({
  name: '',
  description: '',
  start_date: null,
  end_date: null,
  hour: null,
  period: null,
  repeat: false,
  days: {
    Monday: false,
    Tuesday: false,
    Wednesday: false,
    Thursday: false,
    Friday: false,
    Saturday: false,
    Sunday: false,
  },
  usersAssigned: [],
});

const inputSearch = ref(null);
const usersAll = ref([]);
const usersAssigned = ref([]);
const update = ref(false);
const isShowModal = ref(false);
const id = ref(0);

const timeConfig = {
  enableTime: true,
  noCalendar: true,
  dateFormat: 'H:i:ss',
  time_24hr: true,
};

const dateConfig = {
  enableTime: false,
  locale: {
    firstDayOfWeek: 1,
    weekdays: {
      shorthand: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
      longhand: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    },
    months: {
      shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      longhand: [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
      ],
    },
  },
};

const clearForm = () => {
  task.value = {
    name: '',
    description: '',
    start_date: null,
    end_date: null,
    hour: null,
    period: null,
    repeat: false,
    days: {
      Monday: false,
      Tuesday: false,
      Wednesday: false,
      Thursday: false,
      Friday: false,
      Saturday: false,
      Sunday: false,
    },
  };
  update.value = false;
  usersAssigned.value = [];
};

const openFormModal = (taskData) => {
  if (!taskData) {
    clearForm();
  } else {
    update.value = true;
    id.value = taskData.id ?? null;
    task.value = { ...taskData, days: JSON.parse(taskData.days) };
    task.value.repeat = taskData.repeat === 1; // Convierte 1 a true y 0 a false
    usersAssigned.value = taskData.users;
  }
  isShowModal.value = true;
};

const closeFormModal = () => {
  clearForm();
  isShowModal.value = false;
};

const setAllDays = (value) => {
  Object.keys(task.value.days).forEach((day) => {
    task.value.days[day] = value;
  });
};

const addUser = (user) => {
  if (!usersAssigned.value.some(u => u.id === user.id)) {
    usersAssigned.value.push(user);
  }
  inputSearch.value = null;
};

const removeUser = (user) => {
  usersAssigned.value = usersAssigned.value.filter(u => u.id !== user.id);
};

const getUsers = () => {
  axios.get('/web/get_users', { params: { search: inputSearch.value } }).then((response) => {
    usersAll.value = response.data.data;
  });
};

const saveTask = () => {
  const data = {
    id: task.value.id,
    name: task.value.name,
    description: task.value.description,
    start_date: task.value.start_date,
    end_date: task.value.end_date,
    hour: task.value.hour,
    period: task.value.period,
    repeat: task.value.repeat,
    days: task.value.days,
    users: usersAssigned.value,
  };

  axios.post('/web/tasks', data).then(() => {
    closeFormModal();
    showSuccessMessage('Tarea creada exitosamente!');
    emit('reload-table');
  }).catch(error => {
    const errors = error.response.data.errors;
    showErrorGroupMessages(errors);
  });
};

const updateTask = () => {
  const data = {
    id: task.value.id,
    name: task.value.name,
    description: task.value.description,
    start_date: task.value.start_date,
    end_date: task.value.end_date,
    hour: task.value.hour,
    period: task.value.period,
    repeat: task.value.repeat,
    days: task.value.days,
    users: usersAssigned.value,
  };

  axios.put(`/web/tasks/${id.value}`, data).then(() => {
    closeFormModal();
    showSuccessMessage('Tarea actualizada exitosamente!');
    emit('reload-table');
  }).catch(error => {
    console.log(error);
    const errors = error.response.data.errors;
    showErrorGroupMessages(errors);
  });
};

const deleteTask = () => {
  axios.delete(`/web/tasks/${id.value}`).then(() => {
    showSuccessMessage('¡Tarea eliminada exitosamente!');
    emit('reload-table');
  }).catch(error => {
    const errors = error.response.data.errors;
    showErrorGroupMessages(errors);
  });
};

const emit = defineEmits(['reload-table']);

onMounted(() => {
  getUsers();
});

// Exponer métodos
defineExpose({
  openFormModal,
  closeFormModal,
  saveTask,
  updateTask,
  deleteTask,
  clearForm,
  getUsers,
  setAllDays,
  addUser,
  removeUser,
});
</script>