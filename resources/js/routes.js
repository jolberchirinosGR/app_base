import Login from './pages/auth/Login.vue';
import Register from './pages/auth/Register.vue';
import Dashboard from './components/Dashboard.vue';
import Tasks from './pages/task/TaskList.vue';
import MyTasks from './pages/task/MyTasks.vue';
import Users from './pages/user/UserList.vue';
import Game from './components/Game.vue';

export default [
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },

    {
        path: '/register',
        name: 'Register',
        component: Register,
    },

    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
    },

    {
        path: '/users',
        name: 'Usuarios',
        component: Users,
    },

    {
        path: '/tasks',
        name: 'Tareas',
        component: Tasks,
    },

    {
        path: '/my-tasks',
        name: 'Mis tareas',
        component: MyTasks,
    },

    {
        path: '/game',
        name: 'Game',
        component: Game,
    },
]
