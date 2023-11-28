<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthUserStore } from '../../stores/AuthUserStore';

const authUserStore = useAuthUserStore();
const router = useRouter();
const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);

const errorMessage = ref('');
const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.post('/login', form)
        .then(() => {
            router.push('/admin/');
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
        })
        .finally(() => {
            loading.value = false;
        });
};
</script>

<template>
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Registro</b> de Usuario</a>
            </div>
            <div class="card-body">
                <p class="register-box-msg">Crea tu cuenta</p>
                <form @submit.prevent="handleRegistration">
                    <div class="input-group mb-3">
                        <input v-model="formData.username" type="text" class="form-control" placeholder="Nombre de usuario">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="formData.email" type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="formData.password" type="password" class="form-control" placeholder="Contraseña">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span v-else>Registrarse</span>
                            </button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="login.html">Ya tengo una cuenta</a>
                </p>
            </div>
        </div>
    </div>
</template>
