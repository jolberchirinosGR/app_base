<template>
  <fwb-table-row>
    <fwb-table-cell>
      {{ taskData.name }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ taskData.description }}
    </fwb-table-cell>

    <fwb-table-cell>
      {{ getDate(taskData.start_date) }}
    </fwb-table-cell>

    <fwb-table-cell>
      <div class="flex flex-wrap gap-1">
        <fwb-badge class="dark:text-white" size="sm" type="dark" v-for="(user, index) in taskData.users" :key="index">
          {{ user.name }}
        </fwb-badge>
      </div>
    </fwb-table-cell>
    
    <fwb-table-cell class="text-center">
      <strong>
        <font-awesome-icon :class="getIconColor(taskData.status)" :icon="getIcon(taskData.status)" size="lg"/>
        <br>
        {{ getStatusName(taskData.status) }}
      </strong>
    </fwb-table-cell>

    <td class="px-6 py-4">
      <fwb-button class="m-2" gradient="green" @click="editModalTask(taskData)">
        <font-awesome-icon :icon="['fas', 'edit']"/>
        Detalles
      </fwb-button>

      <fwb-button class="m-2" gradient="red" @click="deleteModalTask(taskData)">
        <font-awesome-icon :icon="['fas', 'trash']"/>
        Eliminar
      </fwb-button>
    </td>
  </fwb-table-row>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import type { Task } from '../../types/interfaces'; // Importamos la interfaz Task
import { getIcon, getStatusName, getIconColor, getDate } from '../../types/useTaskUtils'; // Importamos las funciones

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Elementos del Flowbite
import {
  FwbTableCell,
  FwbTableRow,
  FwbButton,
  FwbBadge,
} from 'flowbite-vue';

// Props
const props = defineProps({
  task: {
    type: Object as () => Task, // Especificamos que `task` es de tipo `Task`
    required: true,
  },
});

// Data reactiva
const taskData = ref<Task>(props.task);

// Watch para actualizar `taskData` cuando cambia `props.task`
watch(() => props.task, (newTask: Task) => {
  taskData.value = newTask;
});

// Emit
const emit = defineEmits(['open-update-task', 'open-delete-task']);

// Métodos
const editModalTask = (task: Task) => {
  emit('open-update-task', task);
};

const deleteModalTask = (task: Task) => {
  emit('open-delete-task', task);
};
</script>