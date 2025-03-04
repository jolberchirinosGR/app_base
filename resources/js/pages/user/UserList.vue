<template>
  <div class="block space-y-4 md:flex md:space-y-0 md:space-x-4 md:rtl:space-x-reverse items-center" style="margin-bottom: 2%;">
    <h1 class="text-gray dark:text-white text-xl font-bold flex items-center">
      <font-awesome-icon :icon="['fas', 'users']" class="mr-2"/>
      Listado de usuarios
    </h1>

    <fwb-button class="h-9" gradient="blue" @click="createModalUser">
      <font-awesome-icon :icon="['fas', 'plus']"/>
      Nuevo usuario
    </fwb-button>

    <fwb-dropdown text="Paginación">
      <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200">
        <li v-for="num in paginationOptions" :key="num.value">
          <div class="flex items-center">
            <input type="radio" v-model="paginationNumber" :value="num.value"/>
            <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ num.name }}</label>
          </div>
        </li>
      </ul>
    </fwb-dropdown>

    <fwb-dropdown text="Roles">
      <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200">
        <li v-for="role in roles" :key="role.id">
          <div class="flex items-center">
            <input type="radio" v-model="roleSearch" :value="role.id"/>
            <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ role.name }}</label>
          </div>
        </li>
        <li>
          <div class="flex items-center">
            <input type="radio" v-model="roleSearch" :value="null"/>
            <label class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sin filtrar</label>
          </div>
        </li>
      </ul>
    </fwb-dropdown>

    <fwb-input #prefix v-model="inputSearch" placeholder="Buscador...">
      <font-awesome-icon :icon="['fas', 'search']"/>
    </fwb-input>
  </div>

  <fwb-table>
    <fwb-table-head>
      <fwb-table-head-cell>Nombre</fwb-table-head-cell>
      <fwb-table-head-cell>Correo</fwb-table-head-cell>
      <fwb-table-head-cell>Creado</fwb-table-head-cell>
      <fwb-table-head-cell>Acciones</fwb-table-head-cell>
    </fwb-table-head>

    <fwb-table-body>
      <UserListItem v-for="user in users.data" :key="user.id" :user="user" @open-update-user="updateModalUser" @open-delete-user="deleteModalUser"/>
    </fwb-table-body>
  </fwb-table>

  <nav class="w-full flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
    <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">
      Viendo <span class="font-semibold text-gray-900 dark:text-white">{{ users.from }} - {{ users.to }}</span> de <span class="font-semibold text-gray-900 dark:text-white">{{ users.total }}</span>
    </span>
    <fwb-pagination v-model="users.current_page" :total-pages="users.last_page" @page-changed="getUsers" previous-label="<<<" next-label=">>>"/>
  </nav>

  <user-modals ref="userModals" @reload-table="reloadTable"/>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';
import UserListItem from './UserListItem.vue';
import UserModals from './UserModals.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Role, User } from '../../types/interfaces';
import {
  FwbTable, FwbTableBody, FwbTableHead, FwbTableHeadCell, FwbButton,
  FwbPagination, FwbDropdown, FwbInput
} from 'flowbite-vue';

const users = ref<any>({ data: [], from: 0, to: 0, total: 0, current_page: 1, last_page: 1 });
const roles = ref<Role[]>([]);
const inputSearch = ref<string | null>(null);
const roleSearch = ref<string | null>(null);
const paginationNumber = ref<number>(10);
const orderByColumn = ref<string>('');
const orderByType = ref<string>('none');
const userModals = ref<InstanceType<typeof UserModals> | null>(null);

const paginationOptions = [
  { value: 10, name: '10' },
  { value: 25, name: '25' },
  { value: 50, name: '50' },
  { value: 100, name: '100' }
];

const getUsers = async (page = 1) => {
  const response = await axios.get(`/web/users?page=${page}`, {
    params: { search: inputSearch.value, role: roleSearch.value, pagination: paginationNumber.value, order: orderByType.value, column: orderByColumn.value }
  });
  users.value = response.data;
};

const getRoles = async () => {
  const response = await axios.get('/web/roles');
  roles.value = response.data;
};

const createModalUser = () => {
  userModals.value?.openFormModal(null);
};

const updateModalUser = (data: User) => {
  userModals.value?.openFormModal(data);
};

const deleteModalUser = (data: User) => {
  userModals.value?.openDeleteModal(data);
};

const reloadTable = () => {
  getUsers();
};

watch([inputSearch, paginationNumber, roleSearch, orderByType], debounce(getUsers, 300));

onMounted(() => {
  getUsers();
  getRoles();
});
</script>