<template>
    <div class="grid grid-cols-3 gap-4 mb-4">
        <!-- Listado de las tareas -->
        <fwb-card href="#">
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Noteworthy technology acquisitions 2021
                </h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">
                    Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.
                </p>
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
} from 'flowbite-vue';
import { useAuthUserStore } from '../stores/AuthUserStore';

  export default {    
    components: {
        FwbCard, 
    },
    data() {
      return {
        tasks: [],
      };
    },
    created() {
        const authUserStore = useAuthUserStore();
const user = authUserStore.user;
    },
    watch: {
    },
    methods: {
        //Obtener todas las tareas del usuario
        getTasks() {
            axios.get(`/web/tasks`).then((response) => {
                this.tasks = response.data;
            });
        },
    
    },
  };
</script>