<template>
    <div class="grid grid-cols-5 gap-4 mb-4">

        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Pendientes Hoy:
                </h5>

                <br>

                <fwb-card v-for="(task) in tasksToday">
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

        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Proxima semana:
                </h5>
            </div>
        </fwb-card>
        
        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Dentro de 15 días:
                </h5>
            </div>
        </fwb-card>
        
        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Proximo mes
                </h5>

            </div>
        </fwb-card>

        <!-- Listado de las tareas -->
        <fwb-card>
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Restantes
                </h5>
            </div>
        </fwb-card>
    </div>
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    FwbCard,
    FwbBadge,
    FwbButton,
} from 'flowbite-vue';
import { useAuthUserStore } from '../stores/AuthUserStore';

  export default {    
    components: {
        FwbCard, 
        FwbBadge,
        FwbButton,
    },
    data() {
      return {
        tasksToday: [],
      };
    },
    created() {
        const authUserStore = useAuthUserStore();
        const user = authUserStore.user;

        this.get_task_by_date();
    },
    watch: {
    },
    methods: {
        //Obtener todas las tareas del usuario
        get_task_by_date() {
            const date = new Date();
            const formattedDate = date.toISOString().split('T')[0]; // Obtiene la fecha en formato AAAA-MM-DD

            axios.get(`/web/get_task_by_date`, {
            params: {
                start_date: formattedDate,
            },
            }).then((response) => {
                this.tasksToday = response.data;
                console.log(this.tasksToday);
            });
        },
    },
  };
</script>