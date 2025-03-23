<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const logout = () => {
    localStorage.removeItem('token');
    router.push('/');
};
</script>

<template>
    <div class="container mt-5">
        <h1>Dashboard</h1>
        <p>Chào mừng bạn đến với hệ thống quản lý công việc!</p>
        <button @click="logout" class="btn btn-danger">Đăng xuất</button>
    </div>
</template>
