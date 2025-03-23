<template>
    <div class="container mt-4">
        <h2>✏️ Chỉnh sửa Công Việc</h2>
        <form @submit.prevent="updateTask">
            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input v-model="task.title" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea v-model="task.description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày hết hạn</label>
                <input v-model="task.due_date" type="date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Người nhận</label>
                <select v-model="task.assigned_to" class="form-control">
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select v-model="task.status" class="form-control">
                    <option value="pending">Chưa bắt đầu</option>
                    <option value="in_progress">Đang thực hiện</option>
                    <option value="completed">Đã hoàn thành</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
            <button type="button" class="btn btn-secondary ms-2" @click="goBack">Quay lại</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../api';

const router = useRouter();
const route = useRoute();
const task = ref({});
const users = ref([]);

const fetchTask = async () => {
    try {
        const response = await api.get(`/tasks/${route.params.id}`);
        task.value = response.data;
    } catch (error) {
        console.error('Lỗi khi tải công việc:', error);
    }
};

const fetchUsers = async () => {
    try {
        const response = await api.get('/users');
        users.value = response.data;
    } catch (error) {
        console.error('Lỗi khi tải danh sách người dùng:', error);
    }
};

const updateTask = async () => {
    try {
        // console.log('Updating task with data:', task.value);
        const response = await api.put(`/tasks/${task.value.id}`, task.value);
        // console.log('Update response:', response);
        router.push('/tasks');
    } catch (error) {
        console.error('Lỗi khi cập nhật công việc:', error);
    }
};

const goBack = () => {
    router.push('/tasks');
};

onMounted(() => {
    fetchTask();
    fetchUsers();
});
</script>
