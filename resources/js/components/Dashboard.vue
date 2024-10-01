<template>
    <div class="grid grid-cols-5 gap-4 mb-4">
        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Pendientes Hoy:
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksToday" :key="task.id" class="mb-10">
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <template #icon>
                                    <svg aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd" />
                                    </svg>
                                </template>
                                {{ task.hour }}
                            </fwb-badge>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ task.description }}
                        </span>
                        <div class="flex mt-4 md:mt-6">
                            <fwb-button class="mr-2" gradient="blue" @click="deleteModalUser(userData)">
                                <font-awesome-icon :icon="['fas', 'sync']"/>
                            </fwb-button>
                        </div>
                    </div>
                </fwb-card>
            </div>
        </fwb-card>

        <!-- Proxima semana -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Proxima semana:
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksWeek" :key="task.id" class="mb-10">
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <template #icon>
                                    <svg aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd" />
                                    </svg>
                                </template>
                                {{ task.hour }}
                            </fwb-badge>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ task.description }}
                        </span>
                        <div class="flex mt-4 md:mt-6">
                            <fwb-button class="mr-2" gradient="blue" @click="deleteModalUser(userData)">
                                <font-awesome-icon :icon="['fas', 'sync']"/>
                            </fwb-button>
                        </div>
                    </div>
                </fwb-card>
            </div>
        </fwb-card>

        <!-- Dentro de 15 días -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Dentro de 15 días:
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksTwoWeeks" :key="task.id" class="mb-10">
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }} <!-- Nombre de la tarea -->
                            <fwb-badge> <!-- Hora de ejecucion de la tarea -->
                                <template #icon>
                                    <svg aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd" />
                                    </svg>
                                </template>
                                {{ task.hour }}
                            </fwb-badge>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ task.description }} <!-- Descripcion de la tarea -->
                        </span>
                        <div class="flex mt-4 md:mt-6">
                            <fwb-button class="mr-2" gradient="blue" @click="deleteModalUser(userData)">
                                <font-awesome-icon :icon="['fas', 'sync']"/>
                            </fwb-button>
                        </div>
                    </div>
                </fwb-card>
            </div>
        </fwb-card>

        <!-- Proximo mes -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Proximo mes
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksMonth" :key="task.id" class="mb-10">
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <template #icon>
                                    <svg aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd" />
                                    </svg>
                                </template>
                                {{ task.hour }}
                            </fwb-badge>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ task.description }}
                        </span>
                        <div class="flex mt-4 md:mt-6">
                            <fwb-button class="mr-2" gradient="blue" @click="deleteModalUser(userData)">
                                <font-awesome-icon :icon="['fas', 'sync']"/>
                            </fwb-button>
                        </div>
                    </div>
                </fwb-card>
            </div>
        </fwb-card>

        <!-- Restantes -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Resto del año
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksYear" :key="task.id" class="mb-10">
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <template #icon>
                                    <svg aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" fill-rule="evenodd" />
                                    </svg>
                                </template>
                                {{ task.hour }}
                            </fwb-badge>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ task.description }}
                        </span>
                        <div class="flex mt-4 md:mt-6">
                            <fwb-button class="mr-2" gradient="blue" @click="deleteModalUser(userData)">
                                <font-awesome-icon :icon="['fas', 'sync']"/>
                            </fwb-button>
                        </div>
                    </div>
                </fwb-card>
            </div>
        </fwb-card>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthUserStore } from '../stores/AuthUserStore';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    FwbCard,
    FwbBadge,
    FwbButton,
} from 'flowbite-vue';

// Referencias reactivas
const tasksToday = ref([]);
const tasksWeek = ref([]);
const tasksTwoWeeks = ref([]);
const tasksMonth = ref([]);
const tasksYear = ref([]);

// Store de usuario
const authUserStore = useAuthUserStore();
const user = authUserStore.user;

// Método para obtener las tareas de hoy
const getTasksToday = async () => {
    try {
        const response = await axios.get(`/web/get_tasks_today`);
        tasksToday.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

// Método para obtener las tareas de la siguiente semana 
const getTasksWeek = async () => {
    try {
        const response = await axios.get(`/web/get_tasks_week`);
        tasksWeek.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

// Método para obtener las tareas de la siguiente semana 
const getTasksTwoWeeks = async () => {
    try {
        const response = await axios.get(`/web/get_tasks_two_weeks`);
        tasksTwoWeeks.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

// Método para obtener las tareas de la siguiente semana 
const getTasksMonth = async () => {
    try {
        const response = await axios.get(`/web/get_tasks_month`);
        tasksMonth.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

// Método para obtener las tareas de la siguiente semana 
const getTasksYear = async () => {
    try {
        const response = await axios.get(`/web/get_tasks_year`);
        tasksYear.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    }
};

// Llamada al método al montar el componente
onMounted(() => {
    getTasksToday();
    getTasksWeek();
    getTasksTwoWeeks();
    getTasksMonth();
    getTasksYear();
});
</script>
