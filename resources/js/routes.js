//Rutas Perfil
import Issues from './pages/Issues.vue';
import LostObjects from './pages/LostObjects.vue';
import Dashboard from './components/Dashboard.vue';
import Login from './pages/auth/Login.vue';

export default [
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },

    {
        path: '/',
        name: 'Dashboard',
        component: Issues,
    },

    {
        path: '/issues',
        name: 'Issues',
        component: Issues,
    },

    {
        path: '/lost_objects',
        name: 'LostObjects',
        component: LostObjects,
    },
]
