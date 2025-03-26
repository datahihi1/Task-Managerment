import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import TaskList from '../views/TaskList.vue';
import TaskCreate from '../views/TaskCreate.vue';
import TaskDetail from '../views/TaskDetail.vue';
import TaskEdit from '../views/TaskEdit.vue';
import UserList from '../views/UserList.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/tasks', component: TaskList, meta: { requiresAuth: true } },
    { path: '/tasks/create', component: TaskCreate, meta: { requiresAuth: true } },
    { path: '/tasks/:id', component: TaskDetail, props: true, meta: { requiresAuth: true } },
    { path: '/tasks/:id/edit', component: TaskEdit, props: true, meta: { requiresAuth: true } },
    { path: '/users', component: UserList, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});

export default router;