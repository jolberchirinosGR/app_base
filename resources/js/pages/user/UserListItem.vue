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
      <fwb-button class="mr-2" gradient="green" @click="editModalUser(userData)">
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

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { getDate } from '../../types/useTaskUtils';

//Elementos del flowbite
import {
  FwbTableCell,
  FwbTableRow,
  FwbButton,
} from 'flowbite-vue'

// Propiedades y eventos
const props = defineProps({
  user: Object,
});

const emit = defineEmits(['open-update-user', 'open-delete-user']);

const userData = ref(props.user);

// Watcher para la propiedad 'user'
watch(() => props.user, (newUser) => {
  userData.value = newUser;
});

// Métodos
const editModalUser = (user) => {
  emit('open-update-user', user);
};

const deleteModalUser = (user) => {
  emit('open-delete-user', user);
};
</script>