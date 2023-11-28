<template>
    <nav class="navbar navbar-dark">
      <div class="row">
        <div class="nav-separator"></div> <!-- Separador -->
        <a class="brand-link">
              <img src="../../../public/logo.png"  alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
        </a>

        <div class="nav-separator"></div> <!-- Separador -->
  
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link to="/issues" active-class="active" class="nav-link">
              <span class="d-none d-sm-inline">
                <i class="fas fa-exclamation-triangle"></i>
              </span>
              <span class="d-inline d-sm-none">
                <i class="fas fa-exclamation-triangle"></i>
              </span>
              <span class="ml-1 d-none d-sm-inline">Incidencias</span>
            </router-link>
          </li>
        </ul>
  
        <div class="nav-separator"></div> <!-- Separador -->
  
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link to="/lost_objects" active-class="active" class="nav-link">
              <span class="d-none d-sm-inline">
                <i class="fa fa-box-open"></i>
              </span>
              <span class="d-inline d-sm-none">
                <i class="fa fa-box-open"></i>
              </span>
              <span class="ml-1 d-none d-sm-inline">Objetos Perdidos</span>
            </router-link>
          </li>
        </ul>
      </div>
  
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#" @click.prevent="logout" role="button">
            <span class="d-none d-sm-inline">
              <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="d-inline d-sm-none">
              <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="ml-1 d-none d-sm-inline">Cerrar Sesión</span>
          </a>
        </li>
      </ul>
    </nav>
</template>

<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const authUserStore = useAuthUserStore();

const logout = () => {
    axios.post('/logout')
    .then((response) => {
        authUserStore.user.name = '';
        router.push('/login');
    });
};
</script>

<style>
    .nav-separator {
        width: 20px; /* Ancho del separador */
    }
</style>