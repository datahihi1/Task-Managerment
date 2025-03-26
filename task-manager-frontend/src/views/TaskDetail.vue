<template>
    <div class="container mt-4">
        <h2>Chi Tiết Công Việc</h2>

        <div v-if="loading">⏳ Đang tải...</div>
        <div v-else-if="task">
            <p><strong>Tiêu đề:</strong> {{ task.title }}</p>
            <p><strong>Mô tả:</strong> {{ task.description || "Không có mô tả" }}</p>
            <p><strong>Ngày tạo:</strong> {{ formatDate(task.created_at) }}</p>
            <p><strong>Ngày hết hạn:</strong> {{ task.due_date || "Chưa đặt" }}</p>
            <p><strong>Người nhận:</strong> 
                <span v-if="task.assigned_user">{{ task.assigned_user.name }}</span>
                <span v-else>Chưa phân công</span>
            </p>
            <p><strong>Trạng thái:</strong> {{ task.status }}</p>
            <button class="btn btn-secondary mt-3 me-2" @click="goBack">Quay lại</button>
            <button class="btn btn-warning mt-3 me-2" @click="editTask">Chỉnh sửa</button>
            <button class="btn btn-danger mt-3" @click="confirmDelete">🗑 Xóa</button>
        </div>
        <div v-else>Công việc không tồn tại!</div>

        <div v-if="showConfirmDelete" class="modal show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close" @click="showConfirmDelete = false"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa công việc này không?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="showConfirmDelete = false">Hủy</button>
                        <button class="btn btn-danger" @click="deleteTask">🗑 Xóa</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showConfirmDelete" class="modal-backdrop show"></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';

const route = useRoute();
const router = useRouter();
const task = ref(null);
const loading = ref(true);
const showConfirmDelete = ref(false);

const fetchTask = async () => {
    try {
        const response = await api.get(`/tasks/${route.params.id}`);
        task.value = response.data;
    } catch (error) {
        console.error('Lỗi khi tải công việc:', error);
    } finally {
        loading.value = false;
    }
};

const goBack = () => {
    router.push('/tasks');
};

const editTask = () => {
    router.push(`/tasks/${route.params.id}/edit`);
};

const confirmDelete = () => {
    showConfirmDelete.value = true;
};

const deleteTask = async () => {
    try {
        await api.delete(`/tasks/${route.params.id}`);
        router.push('/tasks');
    } catch (error) {
        console.error('Lỗi khi xóa công việc:', error);
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};

onMounted(fetchTask);
</script>
