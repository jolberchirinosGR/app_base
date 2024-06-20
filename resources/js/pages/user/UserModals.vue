<template>
  <!-- <fwb-modal v-if="isShowModal" @close="closeFormModal" persistent> -->
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
                  <label for="nombre_del_usuario" class="label-form-custom">
                    Nombre
                  </label>
                  <input type="text" name="nombre_del_usuario" id="nombre_del_usuario" v-model="user.name" class="input-form-custom" placeholder="Nombres y Apellidos" autocomplete="off">
                </div>
              <div class="col-span-2 sm:col-span-1">
                  <label for="contraseña_del_usuario" class="label-form-custom">
                    Contraseña
                  </label>
                  <input type="password" name="contraseña_del_usuario" id="contraseña_del_usuario" v-model="user.password" class="input-form-custom" autocomplete="off">
              </div>
              <div class="col-span-2 sm:col-span-1">
                  <label for="confirmar_contraseña" class="label-form-custom">
                    Confirmar contraseña
                  </label>
                  <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" v-model="user.confirm_password" class="input-form-custom" autocomplete="off">
              </div>
              <div class="col-span-1">
                  <label for="correo" class="label-form-custom">
                    Correo electrónico
                  </label>
                  <input type="email" name="correo" id="correo"  v-model="user.email" class="input-form-custom" placeholder="Ejemplo@mail.com" autocomplete="off">
              </div>
              <div class="col-span-1">
                  <label for="rol" class="label-form-custom">
                    Rol
                  </label>
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
         <fwb-button  v-if="update" @click="updateUser" color="green">
          <font-awesome-icon :icon="['fas', 'edit']"/>
            Modificar
         </fwb-button>
         <fwb-button  v-else @click="saveUser" color="green">
          <font-awesome-icon :icon="['fas', 'save']"/>
            Guardar
         </fwb-button>
       </div>
     </template>
  </fwb-modal>
 </template>
 
 <script>
 import axios from 'axios';
 import { debounce } from 'lodash';
 import UserListItem from './UserListItem.vue';
 import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
 import { showSuccessMessage, showErrorMessage, showErrorGroupMessages, useSweetAlert }  from '../../stores/Sweet.js';
 
 // Elementos del flowbite
 import {
   FwbModal,
   FwbButton,
 } from 'flowbite-vue'
 
 export default {
   emits: ['reload-table'], // Eventos que se generan en este componente
   components: {
     UserListItem,
     FwbButton,
     FwbModal,
   },
   data() {
     return {
       // Objeto para la edición
       user: {
         id: '',
         name: '',
         email: '',
         password: '',
         confirm_password: '',
         id_role: '',
       },
       // Modales y títulos
       id: 0,
       errors: null,
       rolesAll: [],
       update: false,
       isShowModal: false,
     };
   },
   methods: {
      // Limpiar formulario
        clearForm() {
          this.user = {
            name: '',
            email: '',
            password: '',
            confirm_password: '',
            id_role: '',
          };
          this.update = false;
        },
 
      // Abrir modal de editar o crear usuario
        openFormModal(user) {
          if (user == null) {
            this.clearForm();
          } else {
            this.update = true;
            this.id = user.id ?? null;
            this.user.id = user.id ?? null;
            this.user.name = user.name ?? null;
            this.user.email = user.email ?? null;
            this.user.id_role = user.id_role ?? null;
          }
          this.isShowModal = true;
        },
 
      // Cerrar modal de editar o crear usuario
        closeFormModal() {
          this.clearForm();
          this.isShowModal = false;
        },

      //Obtener todas los Roles
        get_roles(){
            axios.get('/web/roles')
            .then((response) => {
                this.rolesAll = response.data;
            })
        },

      //Guardar el usuario
        saveUser(){
            if (this.user.password !== this.user.confirm_password) {
                showErrorMessage('¡La contraseña y su confirmación no coinciden!');
            }else{
                const data = {
                    id: this.user.id,
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    id_role: this.user.id_role, // Enviar el ID del rol seleccionado
                };

                axios.post('/web/users', data).then(response => {
                    this.closeFormModal();               
                    showSuccessMessage('¡Usuario creado exitosamente!');
                    this.$emit('reload-table');
                  }).catch(error => {
                    const errors = error.response.data.errors;
                    showErrorGroupMessages(errors)
                });
            }
        },

      //Funcion para actualizar
        updateUser() {
            const userId = this.id;
            let passwordConfirm =  true;

            const data = {
                id: userId,
                name: this.user.name,
                email: this.user.email,
                id_role: this.user.id_role, // Enviar el ID del rol seleccionado
            };

            if(this.passsword !== '' && this.confirm_password !== ''){
                this.user.password == this.user.confirm_password
                    ? data.password = this.user.password
                    : passwordConfirm = false;
            };

            if (passwordConfirm) {
                axios.put(`/web/users/${userId}`, data).then(response => {
                    this.closeFormModal();               
                    showSuccessMessage('¡Usuario actualizado exitosamente!');
                    this.$emit('reload-table');
                  })
                .catch(error => {
                    const errors = error.response.data.errors;
                    showErrorGroupMessages(errors)
                });
            }else{
                showErrorMessage('¡Contraseñas no coinciden!');
            }
        },

      //Abrir modal eliminar usuario
        openDeleteModal(user) {
            this.id = user.id;
            this.showDeleteConfirmation();
        },
        
      //Agrega una nueva función para mostrar la confirmación de eliminación con SweetAlert
        showDeleteConfirmation() {
          const swal = useSweetAlert();
            
            swal.fire({
                title: '¡Advertencia!',
                text: '¿Estás seguro de eliminar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                // Si el usuario confirma la eliminación, llamas a la función deleteUser()
                this.deleteUser();
                }
            });
        },

      //Funcion para eliminar
        deleteUser() {
            axios.delete(`/web/users/${this.id}`,{
                headers: {
                    Authorization: `Bearer ${this.token}`, // Include the token in the headers
                },
            })
            .then(() => {
                showSuccessMessage('¡Usuario eliminado exitosamente!');
                this.$emit('reload-table');
            }).catch(error => {
                const errors = error.response.data.errors;
                showErrorGroupMessages(errors)
            });
        },
   },
   watch: {
 
   },
   created() {
    this.get_roles();
 
   },
   mounted() {
     initFlowbite();
   }
 };
 </script>