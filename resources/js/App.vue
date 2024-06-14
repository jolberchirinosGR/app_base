<template>
  <div v-if="authUserStore.user.name !== ''" id="app">
      <AppNavbar />
      <AppSideMenu />
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
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

const authUserStore = useAuthUserStore();
</script>