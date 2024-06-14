<template>
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Abrir Menu</span>
                    <font-awesome-icon :icon="['fas', 'bars']" bounce size="xl"/>                
                </button>
                <a class="flex ms-2 md:me-24">
                    <img :src="user.theme == 'dark' ? 'logow.webp' : 'logob.webp' " class="h-8 me-3" alt="FlowBite Logo" />
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Jolber Chirinos</span>
                </a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                    <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="https://static8.depositphotos.com/1207999/1027/i/450/depositphotos_10275300-stock-photo-business-man-avatar-profile.jpg" alt="user photo">
                    </button>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                            {{ user.name }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                            {{ user.email }}
                        </p>
                    </div>
                    <ul class="py-1" role="none">
                        <li>
                            <a @click="changeTheme" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                <font-awesome-icon :icon="user.theme == 'light' ? ['fas', 'moon'] : ['fas', 'sun']" />
                                Modo  {{ user.theme == 'light' ? 'Oscuro' : 'Claro' }}
                            </a>
                        </li>
                        <li>
                            <a @click="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                <font-awesome-icon :icon="['fas', 'sign-out-alt']" />
                                Salir
                            </a>
                        </li>
                    </ul>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import axios from 'axios';
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
    initTheme();
})

const router = useRouter();
const authUserStore = useAuthUserStore();
const user = authUserStore.user;

const logout = () => {
    axios.post('/logout').then((response) => {
        authUserStore.user.name = '';
        router.push('/login');
    });
};

const changeTheme = () => {
    axios.get('/web/change_theme').then((response) => {

        authUserStore.user.theme = response.data.theme;

        initTheme();
    });
};

const initTheme = () => {
    authUserStore.user.theme 

    if (authUserStore.user.theme == 'dark') {
        document.documentElement.classList.add('dark');
    } 
        
    if (authUserStore.user.theme == 'light' || authUserStore.user.theme == null) {
        document.documentElement.classList.remove('dark')
    }
};
</script>