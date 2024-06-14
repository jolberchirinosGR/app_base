<template>
  <div v-if="authUserStore.user.name !== ''" id="app">
      <AppNavbar />
      <AppSideMenu />
        <div class="p-4 sm:ml-64 dark:bg-gray-900 min-h-screen">
            <div class="p-4 mt-14">
                <router-view></router-view>
            </div>
        </div>
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