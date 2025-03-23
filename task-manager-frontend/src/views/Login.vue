<script setup>
import { ref } from 'vue';
import api from '../api';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const login = async () => {
    if (!email.value || !password.value) {
        error.value = 'Vui lòng nhập đầy đủ email và mật khẩu.';
        return;
    }
    
    try {
        const response = await api.post('/login', { email: email.value, password: password.value });
        localStorage.setItem('token', response.data.token);
        router.push('/dashboard');
    } catch (err) {
        error.value = 'Đăng nhập thất bại. Kiểm tra lại thông tin!';
    }
};
</script>

<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Đăng nhập</h2>
                <div v-if="error" class="alert alert-danger">{{ error }}</div>
                <div class="mb-3">
                    <input v-model="email" class="form-control" placeholder="Email" type="email" />
                </div>
                <div class="mb-3">
                    <input v-model="password" class="form-control" placeholder="Mật khẩu" type="password" />
                </div>
                <button @click="login" class="btn btn-primary w-100">Đăng nhập</button>
                <p class="mt-3 text-center">
                    Chưa có tài khoản? 
                    <router-link to="/register">Đăng ký</router-link>
                </p>
            </div>
        </div>
    </div>
</template>
