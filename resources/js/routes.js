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
        component: Dashboard,
    },
]
