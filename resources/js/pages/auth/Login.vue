<template>
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <img src="../../../../public/logo.png" alt="Logo del Proyecto" class="logo-image">
          <h1><br><b>APP</b> Base</h1>
        </div>
        <div class="card-body">
          <p class="login-box-msg">Ingresa tus credenciales para iniciar</p>
          <div v-if="errorMessage" class="alert alert-danger" role="alert">
            {{ errorMessage }}
          </div>
          <form @submit.prevent="handleSubmit">
            <div class="input-group mb-3">
              <input v-model="form.email" type="email" class="form-control" placeholder="Correo@ejemplo.com">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input v-model="form.password" type="password" class="form-control" placeholder="C0ntras3ña">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8"></div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                  <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Cargando...</span>
                  </div>
                  <span v-else class="fas fa-sign-in-alt"></span> Iniciar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>
  
<style>
  .logo-image {
    width: 200px;
    height: auto;
    margin-right: 10px;
  }
</style>
  
<script>
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  import { useAuthUserStore } from '../../stores/AuthUserStore';
  import { useToastr } from '../../toastr.js';
  
  export default {
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
        loading: false,
        errorMessage: '',
      };
    },
    methods: {
      handleSubmit() {
        this.loading = true;
        this.errorMessage = '';
        axios.post('/login', this.form)
          .then(() => {
            this.$router.push('/');
          })
          .catch((error) => {
            this.errorMessage = error.response.data.message;
          })
          .finally(() => {
            this.loading = false;
          });
      },
    },
  };
  </script>