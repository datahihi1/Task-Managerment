<script setup>
import { ref } from 'vue';
import api from '../api';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const register = async () => {
    if (!name.value || !email.value || !password.value) {
        error.value = 'Vui lòng nhập đầy đủ thông tin.';
        return;
    }

    try {
        await api.post('/register', { name: name.value, email: email.value, password: password.value });
        router.push('/');
    } catch (err) {
        error.value = 'Đăng ký thất bại. Hãy thử lại!';
    }
};
</script>

<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">Đăng ký</h2>
                <div v-if="error" class="alert alert-danger">{{ error }}</div>
                <div class="mb-3">
                    <input v-model="name" class="form-control" placeholder="Tên" type="text" />
                </div>
                <div class="mb-3">
                    <input v-model="email" class="form-control" placeholder="Email" type="email" />
                </div>
                <div class="mb-3">
                    <input v-model="password" class="form-control" placeholder="Mật khẩu" type="password" />
                </div>
                <button @click="register" class="btn btn-success w-100">Đăng ký</button>
                <p class="mt-3 text-center">
                    Đã có tài khoản? 
                    <router-link to="/">Đăng nhập</router-link>
                </p>
            </div>
        </div>
    </div>
</template>
