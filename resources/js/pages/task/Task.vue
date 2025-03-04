<template>
  <!-- Mis tareas version panel -->
  <my-tasks ref="myTasks" v-if="viewPanel"
    @open-new-task="createModalTask"
    @open-update-task="updateModalTask"
    @open-delete-task="deleteModalTask"
    @change-view="changeView"
  />

  <!-- Listado de tareas version panel -->
  <task-list ref="taskList" v-else
    @open-new-task="createModalTask"
    @open-update-task="updateModalTask"
    @open-delete-task="deleteModalTask"
    @change-view="changeView"
  />

  <!-- Modal -->
  <task-modals ref="taskModals"
    @reload="reload"
  />
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import TaskModals from './TaskModals.vue';
import MyTasks from './MyTasks.vue';
import TaskList from './TaskList.vue';

const viewPanel = ref(true);
const taskModals = ref(null);
const myTasks = ref(null);
const taskList = ref(null);

const createModalTask = () => {
  taskModals.value.openFormModal(null);
};

const updateModalTask = (data) => {
  taskModals.value.openFormModal(data);
};

const deleteModalTask = (data) => {
  taskModals.value.openDeleteModal(data);
};

const changeView = () => {
  viewPanel.value = !viewPanel.value;
};

const reload = async () => {
  myTasks.value.getTasks();
  taskList.value.getTasks(); 
};
</script>