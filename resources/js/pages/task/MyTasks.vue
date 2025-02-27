<template>
    <div class="grid grid-cols-5 gap-4 mb-4">
        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Pendientes Hoy:
                </h5>

                <fwb-spinner size="15" v-if="dayLoading"/>

                <fwb-card v-for="(task) in tasksToday" :key="task.id" class="mb-10" v-else>
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <font-awesome-icon :icon="['fa', 'clock']" class="m-1"/>
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

                <fwb-spinner size="15" v-if="weekLoading"/>

                <fwb-card v-for="(task) in tasksWeek" :key="task.id" class="mb-10" v-else>
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <font-awesome-icon :icon="['fa', 'clock']" class="m-1"/>
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

                <fwb-spinner size="15" v-if="twoWeekLoading"/>

                <fwb-card v-for="(task) in tasksTwoWeeks" :key="task.id" class="mb-10" v-else>
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }} <!-- Nombre de la tarea -->
                            <fwb-badge> <!-- Hora de ejecucion de la tarea -->
                                <font-awesome-icon :icon="['fa', 'clock']" class="m-1"/>
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

                <fwb-spinner size="15" v-if="monthLoading"/>

                <fwb-card v-for="(task) in tasksMonth" :key="task.id" class="mb-10" v-else>
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <font-awesome-icon :icon="['fa', 'clock']" class="m-1"/>
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

                <fwb-spinner size="15" v-if="yearLoading"/>

                <fwb-card v-for="(task) in tasksYear" :key="task.id" class="mb-10" v-else>
                    <div class="flex flex-col items-center p-5">
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">
                            {{ task.name }}
                            <fwb-badge>
                                <font-awesome-icon :icon="['fa', 'clock']" class="m-1"/>
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
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useAuthUserStore } from '../../stores/AuthUserStore';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    FwbCard,
    FwbBadge,
    FwbButton,
    FwbSpinner,
} from 'flowbite-vue';

// Referencias reactivas
const tasksToday = ref([]);
const tasksWeek = ref([]);
const tasksTwoWeeks = ref([]);
const tasksMonth = ref([]);
const tasksYear = ref([]);

// Variable reactiva para el estado de carga de las tareas del día
const dayLoading = ref(true);
const weekLoading = ref(true);
const twoWeekLoading = ref(true);
const monthLoading = ref(true);
const yearLoading = ref(true);

// Store de usuario
const authUserStore = useAuthUserStore();
const user = authUserStore.user;

// Método para obtener las tareas de hoy
const getTasksToday = async () => {
    dayLoading.value = true; // Inicia la carga
    try {
        const response = await axios.get(`/web/get_tasks_today`);
        tasksToday.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        dayLoading.value = false; // Finaliza la carga independientemente del resultado
    }
};

// Método para obtener las tareas de la siguiente semana 
const getTasksWeek = async () => {
    weekLoading.value = true; // Inicia la carga

    try {
        const response = await axios.get(`/web/get_tasks_week`);
        tasksWeek.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        weekLoading.value = false; // Finaliza la carga independientemente del resultado
    }
};

const getTasksTwoWeeks = async () => {
    twoWeekLoading.value = true; // Inicia la carga

    try {
        const response = await axios.get(`/web/get_tasks_two_weeks`);
        tasksTwoWeeks.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        twoWeekLoading.value = false; // Finaliza la carga independientemente del resultado
    }
};

const getTasksMonth = async () => {
    monthLoading.value = true; // Inicia la carga

    try {
        const response = await axios.get(`/web/get_tasks_month`);
        tasksMonth.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        monthLoading.value = false; // Finaliza la carga independientemente del resultado
    }
};

const getTasksYear = async () => {
    yearLoading.value = true; // Inicia la carga

    try {
        const response = await axios.get(`/web/get_tasks_year`);
        tasksYear.value = response.data;
    } catch (error) {
        console.error('Error fetching tasks:', error);
    } finally {
        yearLoading.value = false; // Finaliza la carga independientemente del resultado
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
