<template>
    <div class="container mt-4">
        <h2>Danh sách Công Việc</h2>

        <button class="btn btn-primary mb-3" @click="goToCreateTask">Tạo Công Việc</button>

        <div v-if="loading">Đang tải danh sách công việc...</div>
        <div v-else-if="tasks.length === 0">Không có công việc nào!</div>
        <ul class="list-group">
        <li v-for="task in tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
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
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const tasks = ref([]);
const loading = ref(true);

const fetchTasks = async () => {
    try {
        const response = await api.get('/tasks');
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
            return 'badge-success';
        case 'in-progress':
            return 'badge-warning';
        case 'pending':
            return 'badge-secondary';
        default:
            return 'badge-light';
    }
};

const getStatusText = (status) => {
    switch (status) {
        case 'completed':
            return 'Hoàn thành';
        case 'in-progress':
            return 'Đang tiến hành';
        case 'pending':
            return 'Chờ xử lý';
        default:
            return 'Không xác định';
    }
};

onMounted(() => {
    fetchTasks();
});
</script>


<style scoped>
.task-title {
    cursor: pointer;
    color: blue;
}

.task-title:hover {
    text-decoration: underline;
}
</style>
