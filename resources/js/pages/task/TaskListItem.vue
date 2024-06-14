<template>

    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ userData.name }}
      </th>
      <td class="px-6 py-4">
        {{ userData.email }}
      </td>
      <td class="px-6 py-4">
        {{ getDate(userData.created_at) }}
      </td>
      <td class="px-6 py-4">
        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
          <font-awesome-icon :icon="['fas', 'edit']"/>
          Editar
        </button>

        <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
          <font-awesome-icon :icon="['fas', 'trash']"/>
          Eliminar
        </button>

      </td>
    </tr>
</template>
  
<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

  export default {
    emits: ['open-update-user', 'open-delete-user'], //Eventos que se generan en este componente
    components: {
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