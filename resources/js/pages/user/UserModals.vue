<template>
  <fwb-modal v-if="isShowModal" @close="closeFormModal" persistent class="fixed top-0 left-0 right-0 z-50">
    <template #header>
      <div class="flex items-center text-lg text-gray-500 dark:text-white">
        <strong>
          {{ update ? 'Editar Usuario': 'Nuevo Usuario' }}
        </strong>
      </div>
    </template>
    <template #body>
      <form class="p-4 md:p-5">
        <div class="grid gap-4 mb-4 grid-cols-2">
          <div class="col-span-2">
            <label for="nombre_del_usuario" class="label-form-custom">Nombre</label>
            <input type="text" name="nombre_del_usuario" id="nombre_del_usuario" v-model="user.name" class="input-form-custom" placeholder="Nombres y Apellidos" autocomplete="off">
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="contraseña_del_usuario" class="label-form-custom">Contraseña</label>
            <input type="password" name="contraseña_del_usuario" id="contraseña_del_usuario" v-model="user.password" class="input-form-custom" autocomplete="off">
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="confirmar_contraseña" class="label-form-custom">Confirmar contraseña</label>
            <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" v-model="user.confirm_password" class="input-form-custom" autocomplete="off">
          </div>
          <div class="col-span-1">
            <label for="correo" class="label-form-custom">Correo electrónico</label>
            <input type="email" name="correo" id="correo" v-model="user.email" class="input-form-custom" placeholder="Ejemplo@mail.com" autocomplete="off">
          </div>
          <div class="col-span-1">
            <label for="rol" class="label-form-custom">Rol</label>
            <select v-model="user.id_role" class="input-form-custom" required>
              <option v-for="rol in rolesAll" :value="rol.id" :key="rol.id">{{ rol.name }}</option>
            </select>
          </div>
        </div>
      </form>
    </template>
    <template #footer>
      <div class="flex justify-between">
        <fwb-button @click="closeFormModal" color="alternative">
          <font-awesome-icon :icon="['fas', 'times']"/>
          Cerrar
        </fwb-button>
        <fwb-button v-if="update" @click="updateUser" color="green">
          <font-awesome-icon :icon="['fas', 'edit']"/>
          Modificar
        </fwb-button>
        <fwb-button v-else @click="saveUser" color="blue">
          <font-awesome-icon :icon="['fas', 'save']"/>
          Guardar
        </fwb-button>
      </div>
    </template>
  </fwb-modal>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { showSuccessMessage, showErrorMessage, showErrorGroupMessages, useSweetAlert } from '../../stores/Sweet';

// Elementos del flowbite
import { FwbModal, FwbButton } from 'flowbite-vue';

// Definir el evento 'reload-table' para emitir desde el componente
const emit = defineEmits(['reload-table']);

// Definición de los campos reactivos
const user = ref({
  id: '',
  name: '',
  email: '',
  password: '',
  confirm_password: '',
  id_role: '',
});

const rolesAll = ref([]);
const update = ref(false);
const isShowModal = ref(false);
const id = ref(0);
const errors = ref(null);

// Funciones y métodos
const clearForm = () => {
  user.value = {
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    id_role: '',
  };
  update.value = false;
};

const openFormModal = (userData) => {
  if (!userData) {
    clearForm();
  } else {
    update.value = true;
    id.value = userData.id ?? null;
    user.value = { ...userData };
  }
  isShowModal.value = true;
};

const closeFormModal = () => {
  clearForm();
  isShowModal.value = false;
};

const get_roles = () => {
  axios.get('/web/roles').then((response) => {
    rolesAll.value = response.data;
  });
};

const saveUser = () => {
  if (user.value.password !== user.value.confirm_password) {
    showErrorMessage('¡La contraseña y su confirmación no coinciden!');
  } else {
    const data = {
      id: user.value.id,
      name: user.value.name,
      email: user.value.email,
      password: user.value.password,
      id_role: user.value.id_role,
    };

    axios.post('/web/users', data)
      .then(() => {
        closeFormModal();
        showSuccessMessage('¡Usuario creado exitosamente!');
        emit('reload-table');
      })
      .catch((error) => {
        const errors = error.response.data.errors;
        showErrorGroupMessages(errors);
      });
  }
};

const updateUser = () => {
  const userId = id.value;
  let passwordConfirm = true;

  const data = {
    id: userId,
    name: user.value.name,
    email: user.value.email,
    id_role: user.value.id_role,
  };

  if (user.value.password !== '' && user.value.confirm_password !== '') {
    if (user.value.password === user.value.confirm_password) {
      data.password = user.value.password;
    } else {
      passwordConfirm = false;
    }
  }

  if (passwordConfirm) {
    axios.put(`/web/users/${userId}`, data)
      .then(() => {
        closeFormModal();
        showSuccessMessage('¡Usuario actualizado exitosamente!');
        emit('reload-table');
      })
      .catch((error) => {
        const errors = error.response.data.errors;
        showErrorGroupMessages(errors);
      });
  } else {
    showErrorMessage('¡Contraseñas no coinciden!');
  }
};

const openDeleteModal = (userData) => {
  id.value = userData.id;
  showDeleteConfirmation();
};

const showDeleteConfirmation = () => {
  const swal = useSweetAlert();

  swal.fire({
    title: '¡Advertencia!',
    text: '¿Estás seguro de eliminar este usuario?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      deleteUser();
    }
  });
};

const deleteUser = () => {
  axios.delete(`/web/users/${id.value}`, {
    headers: {
      Authorization: `Bearer ${token}`, // Agregar token en los headers
    },
  })
    .then(() => {
      showSuccessMessage('¡Usuario eliminado exitosamente!');
      emit('reload-table');
    })
    .catch((error) => {
      const errors = error.response.data.errors;
      showErrorGroupMessages(errors);
    });
};

// Obtener roles al montar el componente
onMounted(() => {
  get_roles();
});

// Exponer estos campos para que otros componentes los puedan acceder
defineExpose({
  user,
  rolesAll,
  update,
  isShowModal,
  id,
  errors,
  clearForm,
  openFormModal,
  closeFormModal,
  get_roles,
  saveUser,
  updateUser,
  openDeleteModal,
  showDeleteConfirmation,
  deleteUser,
});
</script>