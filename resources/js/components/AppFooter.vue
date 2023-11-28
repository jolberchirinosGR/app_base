<template>
    <footer class="footer">
        <div class="float-right d-none d-sm-inline">
            {{ authUserStore.user.name }}
            {{ authUserStore.user.id }}
            <i class="fa fa-ghost fa-spin" style="color: #007bff;"></i>
        </div>
        <strong>Grupo Ruiz &copy; 2023.</strong>Todos los derechos reservados.
    </footer>
</template>

<script setup>
    import { useAuthUserStore } from '../stores/AuthUserStore';

    const authUserStore = useAuthUserStore();

</script>
