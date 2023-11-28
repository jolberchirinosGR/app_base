<template>
  <div v-if="authUserStore.user.name !== ''" class="wrapper" id="app">
      <AppNavbar />
          <router-view></router-view>
      <AppFooter />
  </div>
  <div v-else class="login-page" >
      <router-view></router-view>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import AppFooter from './components/AppFooter.vue';
import { useAuthUserStore } from './stores/AuthUserStore';
import AppSideMenu from './components/AppSideMenu.vue';
const authUserStore = useAuthUserStore();
</script>

<style>
  .main-content {
    padding-top: 20px;
    min-height: calc(100vh - 120px);
  }
  .footer {
    background-color: #343a40;
    color: white;
    padding: 20px ;
    text-align: center;
  }
</style>