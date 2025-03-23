import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue'; // Kiểm tra đường dẫn
import TaskList from '../views/TaskList.vue';
import TaskCreate from '../views/TaskCreate.vue';
import TaskDetail from '../views/TaskDetail.vue';
import TaskEdit from '../views/TaskEdit.vue';



const routes = [
    { path: '/', component: Login },
    { path: '/register', component: Register },
    { path: '/dashboard', component: Dashboard }
];
routes.push(
    { path: '/tasks', component: TaskList },
    { path: '/tasks/create', component: TaskCreate },
    { path: '/tasks/:id', component: TaskDetail, props: true },
    { path: '/tasks/:id/edit', component: TaskEdit, props: true }

);
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
