<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const newTask = ref({ title: '', description: '', due_date: '', assigned_to: '' });
const users = ref([]);
const loadingUsers = ref(true);

const fetchUsers = async () => {
    try {
        const response = await api.get('/users');
        users.value = response.data;
    } catch (error) {
        console.error('Lỗi khi lấy danh sách người dùng:', error);
    } finally {
        loadingUsers.value = false;
    }
};

const createTask = async () => {
    try {
        await api.post('/tasks', newTask.value);
        router.push('/tasks');
    } catch (error) {
        console.error('Lỗi khi tạo công việc:', error);
    }
};

onMounted(fetchUsers);
</script>

<template>
    <div class="container mt-4">
        <h2>Tạo Công Việc Mới</h2>
        <form @submit.prevent="createTask">
            <div class="mb-3">
                <label class="form-label">Tên công việc</label>
                <input v-model="newTask.title" class="form-control" required placeholder="Nhập tên công việc" />
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea v-model="newTask.description" class="form-control" placeholder="Mô tả công việc"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày hết hạn</label>
                <input v-model="newTask.due_date" type="date" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Phân công cho</label>
                <select v-model="newTask.assigned_to" class="form-select">
                    <option value="" disabled>Chọn người nhận công việc</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <div v-if="loadingUsers">Đang tải danh sách người dùng...</div>
            </div>
            <button class="btn btn-success w-100">Thêm Công Việc</button>
        </form>
        <button class="btn btn-secondary mt-3 w-100" @click="router.push('/tasks')">Quay lại</button>
    </div>
</template>
