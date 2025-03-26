<template>
    <div class="container mt-4">
        <button class="btn btn-danger position-absolute top-0 end-0 m-3" @click="logout">
            Đăng xuất
        </button>

        <h2>Danh sách Công Việc</h2>

        <button class="btn btn-primary mb-3" @click="goToCreateTask">Tạo Công Việc</button>

        <div class="mb-3">
            <label for="statusFilter" class="form-label">Lọc theo trạng thái:</label>
            <select id="statusFilter" class="form-select" v-model="statusFilter" @change="fetchTasks">
                <option value="">Tất cả</option>
                <option value="pending">Chờ xử lý</option>
                <option value="in_progress">Đang tiến hành</option>
                <option value="completed">Hoàn thành</option>
            </select>
        </div>

        <div class="form-check mb-3">
            <input
                class="form-check-input"
                type="checkbox"
                id="filterByUser"
                v-model="filterByCurrentUser"
                @change="fetchTasks"
            />
            <label class="form-check-label" for="filterByUser">
                Chỉ hiển thị công việc của tôi
            </label>
        </div>

        <div v-if="loading">Đang tải danh sách công việc...</div>
        <div v-else-if="tasks.length === 0">Không có công việc nào!</div>
        <ul class="list-group">
            <li v-for="task in tasks" :key="task.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 @click="goToTaskDetail(task.id)" class="task-title">{{ task.title }}</h5>
                    <p class="mb-1"><strong>Ngày hết hạn:</strong> {{ formatDate(task.due_date) }}</p>
                    <p class="mb-1"><strong>Người nhận:</strong> {{ task.assigned_user ? task.assigned_user.name : 'Chưa phân công' }}</p>
                    <span class="badge" :class="getStatusClass(task.status)">{{ getStatusText(task.status) }}</span>
                </div>
                <div>
                    <button class="btn btn-warning btn-sm me-2" @click="goToEditTask(task.id)">Chỉnh sửa</button>
                    <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Xóa</button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, onBeforeRouteUpdate } from 'vue-router';
import api from '../api';

const router = useRouter();
const tasks = ref([]);
const loading = ref(true);
const statusFilter = ref('');
const filterByCurrentUser = ref(false);


const fetchTasks = async () => {
    loading.value = true;
    try {
        const params = {};
        if (statusFilter.value) {
            params.status = statusFilter.value;
        }
        if (filterByCurrentUser.value) {
            params.assigned_to_me = true;
        }
        const response = await api.get('/tasks', { params });
        tasks.value = response.data;
    } catch (error) {
        console.error('Lỗi khi tải danh sách công việc:', error);
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    if (!dateString) return 'Không có ngày';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
};

const goToCreateTask = () => {
    router.push('/tasks/create');
};

const goToTaskDetail = (taskId) => {
    router.push(`/tasks/${taskId}`);
};

const goToEditTask = (taskId) => {
    router.push(`/tasks/${taskId}/edit`);
};

const deleteTask = async (taskId) => {
    try {
        await api.delete(`/tasks/${taskId}`);
        tasks.value = tasks.value.filter(task => task.id !== taskId);
    } catch (error) {
        console.error('Lỗi khi xóa công việc:', error);
    }
};

const getStatusClass = (status) => {
    switch (status) {
        case 'completed':
            return 'badge bg-success';
        case 'in_progress':
            return 'badge bg-warning text-dark';
        case 'pending':
            return 'badge bg-secondary';
        default:
            return 'badge bg-light text-dark';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'completed':
            return 'Hoàn thành';
        case 'in_progress':
            return 'Đang tiến hành';
        case 'pending':
            return 'Chờ xử lý';
        default:
            return 'Không xác định';
    }
};

const logout = () => {
    localStorage.removeItem('token');
    router.push('/login');
};

onMounted(() => {
    fetchTasks();
});

onBeforeRouteUpdate(() => {
    fetchTasks();
});
</script>
