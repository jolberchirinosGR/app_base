<template>
  <aside class="main-sidebar sidebar-dark-primary">
      <div class="sidebar-wrapper">
          <a class="brand-link">
              <img src="../../../public/logo.png"  alt="AdminLTE Logo"
                  class="brand-image img-circle elevation-3">
          </a>

          <div class="sidebar">
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar" data-widget="treeview" role="menu" data-accordion="false">
                      <!-- Empresas -->
                      <li class="nav-item" >
                          <router-link to="/"
                              :class="$route.path.startsWith('/admin/companies') ? 'active' : ''" class="nav-link">
                              <i class="nav-icon fas fa-building"></i>
                              <p>
                                  Empresas
                              </p>
                          </router-link>
                      </li>

                      <!-- Contraseñas -->
                      <li class="nav-item" >
                          <router-link to="/"
                              :class="$route.path.startsWith('/admin/passwords') ? 'active' : ''" class="nav-link">
                              <i class="nav-icon fas fa-unlock"></i>
                              <p>
                                  Contraseñas
                              </p>
                          </router-link>
                      </li>
                  </ul>
              </nav>
          </div>
      </div>
  </aside>
</template>

<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const authUserStore = useAuthUserStore();

</script>