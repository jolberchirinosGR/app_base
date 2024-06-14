<template>
        <div class="p-4 sm:ml-64  dark:bg-gray-900">
        <footer class="bg-white rounded-lg shadow sm:flex sm:items-center sm:justify-between p-4 sm:p-6 xl:p-8 dark:bg-gray-800 antialiased">
            <p class="mb-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:mb-0">
                &copy; 2024 <a href="www.linkedin.com/in/jolber-chirinos18" class="hover:underline" target="_blank">Jolber Chirinos</a>. Todos los derechos reservados.
            </p>
        </footer>
    </div>
</template>

<script setup>
    import { useAuthUserStore } from '../stores/AuthUserStore';

    const authUserStore = useAuthUserStore();
</script>
