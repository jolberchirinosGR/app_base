import 'flowbite';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
import App from './App.vue';
import { useAuthUserStore } from './stores/AuthUserStore';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons'; // Importa los iconos que necesitas

// Añadir iconos a la librería
library.add(fas);

const pinia = createPinia();
const app = createApp(App);

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

router.beforeEach(async (to) => {
    const authUserStore = useAuthUserStore();
    
    // Comprobar usuario logueado o no
    if (authUserStore.user.name === '' && to.path !== '/login') {
        await Promise.all([
            authUserStore.getAuthUser(),
        ]);
    }
});

app.use(pinia);
app.use(router);

// Registrar el componente FontAwesomeIcon globalmente
app.component('font-awesome-icon', FontAwesomeIcon);

app.mount('#app');
