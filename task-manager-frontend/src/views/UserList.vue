<template>
    <div class="container mt-4">
        <h2>Quản Lý Người Dùng</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';

const users = ref([]);

const fetchUsers = async () => {
    const response = await api.get('/users');
    users.value = response.data;
};

const deleteUser = async (id) => {
    await api.delete(`/users/${id}`);
    users.value = users.value.filter(user => user.id !== id);
};

onMounted(fetchUsers);
</script>
