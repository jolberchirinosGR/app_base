<template>
    <fwb-modal v-if="isShowModal" @close="closeFormModal"  size="4xl" persistent class="fixed top-0 left-0 right-0 z-50">

     <template #header>
       <div class="flex items-center text-lg text-gray-500 dark:text-white">
        <strong>
          <font-awesome-icon :icon="['fas', 'list-check']"/>
         {{ update ? 'Editar Tarea': 'Nueva Tarea' }}
        </strong>
       </div>
     </template>


     <template #body>
        <form class="p-4 md:p-5">
          <div class="grid gap-4 mb-4 grid-cols-3">
              <div class="col-span-3">
                <fwb-input v-model="task.name" label="Titulo de la tarea" placeholder="Ingresa el titulo de la tarea" />
              </div>

              <div class="col-span-3">
                <fwb-textarea v-model="task.description" label="Descripción de la tarea"  placeholder="Ingresa la descripción detallada" />
              </div>

              <div class="col-span-1">
                <label class="label-form-custom">
                  Fecha inicio de la tarea
                </label>
                <flat-pickr v-model="task.start_date" :config="dateConfig" class="input-form-custom"/>
              </div>   

              <div class="col-span-1">
                <label class="label-form-custom">
                  Hora de ejecución de la tarea
                </label>
                <flat-pickr v-model="task.hour" :config="timeConfig" class="input-form-custom"/>
              </div>   

              <div class="col-span-1">
                <label class="label-form-custom">
                  ¿Quieres repetir esta tarea?
                </label>
                <fwb-toggle v-model="task.repeat" :label="task.repeat ? 'Si' : 'No'" />
              </div>   

              <!-- <div class="col-span-1" v-if="task.repeat">
                <label class="label-form-custom">
                  Fecha fin de la tarea
                </label>
                <flat-pickr v-model="task.end_date" :config="dateConfig" class="input-form-custom"/>
              </div>    -->

              <div class="col-span-3" v-if="task.repeat">
                <label class="label-form-custom">
                  Repetir la tarea:
                </label>
                <div class="sm: flex space-x-4">
                  <fwb-checkbox @click="setAllDays($event.target.checked)" label="Todos los días"/>
                  <fwb-checkbox v-model="task.days['Monday']" label="Lunes"/>
                  <fwb-checkbox v-model="task.days['Tuesday']" label="Martes"/>
                  <fwb-checkbox v-model="task.days['Wednesday']" label="Miércoles"/>
                  <fwb-checkbox v-model="task.days['Thursday']" label="Jueves"/>
                  <fwb-checkbox v-model="task.days['Friday']" label="Viernes"/>
                  <fwb-checkbox v-model="task.days['Saturday']" label="Sábado"/>
                  <fwb-checkbox v-model="task.days['Sunday']" label="Domingo"/>
                </div>
              </div>

              <br>

              <div class="col-span-3" v-if="task.repeat">
                <label class="label-form-custom">
                  Selecciona el período que deseas:
                </label>
                <div class="sm: flex">
                  <fwb-radio v-model="task.period" label="Proximos 7 Días" value="week"/>
                  <fwb-radio v-model="task.period" label="Proximos 15 Días" value="2weeks"/>
                  <fwb-radio v-model="task.period" label="Proximos 30 Días" value="month"/>
                  <fwb-radio v-model="task.period" label="Todo el año" value="year"/>
                </div>
              </div>

              <br>

              <div class="col-span-3">
                <fwb-input v-model="inputSearch" label="Asigna usuarios a esta tarea" placeholder="Buscar usuarios por nombre" />

                <!-- Resultados de la búsqueda en forma de listado -->
                <ul v-show="inputSearch && usersAll.length > 0" class="autocomplete-results input-form-custom">
                  <li v-for="result in usersAll" @click="addUser(result)" :key="result.id">
                    {{ result.name }} - {{ result.email }}
                  </li>
                </ul>
              </div>

              <div class="col-span-1">
                <label class="label-form-custom">
                  Usuarios asignados a esta tarea
                </label>
                <!-- Listado de usuarios ya seleccionados -->
                <fwb-list-group>
                  <fwb-list-group-item hover v-for="(user, index) in usersAssigned" @click="removeUser(user)" class="input-form-custom">
                    {{ user.name }}
                    <font-awesome-icon :icon="['fas', 'user-times']"/>
                  </fwb-list-group-item>
                </fwb-list-group>              
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
         <fwb-button  v-if="update" @click="updateTask" color="green">
          <font-awesome-icon :icon="['fas', 'edit']"/>
            Modificar
         </fwb-button>
         <fwb-button  v-else @click="saveTask" color="green">
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
 import flatPickr from 'vue-flatpickr-component';
 import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
 import { showSuccessMessage, showErrorMessage, showErrorGroupMessages, useSweetAlert }  from '../../stores/Sweet.js';

 // Elementos del flowbite
 import {
   FwbModal,
   FwbButton,
   FwbCheckbox,
   FwbInput,
   FwbRadio,
   FwbTextarea,
   FwbToggle,
   FwbListGroup, 
   FwbListGroupItem,
 } from 'flowbite-vue'
 
 export default {
   emits: ['reload-table'], // Eventos que se generan en este componente
   components: {
     FwbButton,
     FwbCheckbox,
     FwbInput,
     FwbModal,
     FwbRadio,
     FwbTextarea,
     FwbToggle,
     FwbListGroup, 
     FwbListGroupItem,
     flatPickr,
   },
   data() {
     return {
       //Objeto de datos
        task: {
          name: '',
          description: '',
          start_date: null,
          end_date: null,
          hour: null,
          period: null,
          repeat: false,
          days: {
            'Monday': false,
            'Tuesday': false,
            'Wednesday': false,
            'Thursday': false,
            'Friday': false,
            'Saturday': false,
            'Sunday': false,
          },
          usersAssigned: [],
        },
       // Modales y títulos
        id: 0,
        usersAll: [],
        usersAssigned: [],
        update: false,
        isShowModal: false,
        inputSearch: null,

       //Configuraciones del calendario hora 
        timeConfig: {
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i:ss",
          time_24hr: true,
        },

       //Configuracion de calendario fecha
        dateConfig: {
          enableTime: false,
          locale: {
            firstDayOfWeek: 1,
            weekdays: {
              shorthand: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
              longhand: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            },
            months: {
              shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
              longhand: [
                'Enero',
                'Febrero',
                'Marzo',
                'Abril',
                'Mayo',
                'Junio',
                'Julio',
                'Agosto',
                'Septiembre',
                'Octubre',
                'Noviembre',
                'Diciembre',
              ],
            },
          },
        },
     };
   },
   methods: {
      // Limpiar formulario
        clearForm() {
          this.task = {
            name: '',
            description: '',
            start_date: null,
            end_date: null,
            hour: null,
            period: null,
            repeat: false,
            days: {
              'Monday': false,
              'Tuesday': false,
              'Wednesday': false,
              'Thursday': false,
              'Friday': false,
              'Saturday': false,
              'Sunday': false,
            }
          };
          this.update = false;
          this.usersAssigned = [];
        },
 
      // Abrir modal de editar o crear usuario
        openFormModal(task) {
          if (task == null) {
            this.clearForm();
          } else {
            this.update = true;
            this.id = task.id ?? null;
            this.task.id = task.id ?? null;
            this.task.name = task.name ?? null;
            this.task.description = task.description ?? null;
            this.task.start_date = task.start_date ?? null;
            this.task.end_date = task.end_date ?? null;
            this.task.hour = task.hour ?? null;
            this.task.period = task.period ?? null;
            this.task.repeat = !!task.repeat ?? false;

            //Cargar con datos de los dias
            let daysObject = JSON.parse(task.days);
            this.task.days = daysObject;

            this.usersAssigned = task.users;
          }
          this.isShowModal = true;
        },
 
      // Cerrar modal de editar o crear usuario
        closeFormModal() {
          this.clearForm();
          this.isShowModal = false;
        },

      //Abrir modal eliminar usuario
        openDeleteModal(task) {
            this.id = task.id;
            this.showDeleteConfirmation();
        },

      //Agrega una nueva función para mostrar la confirmación de eliminación con SweetAlert
        showDeleteConfirmation() {
          const swal = useSweetAlert();
            
            swal.fire({
                title: '¡Advertencia!',
                text: '¿Estás seguro de eliminar esta tarea?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                  this.deleteTask();
                }
            });
        },

      //Obtener todas los usuarios
        getUsers(){
          axios.get('/web/get_users', {
            params: {
                //Filtrar por usuarios
                search: this.inputSearch,
            },
          }).then((response) => {
              this.usersAll = response.data.data;
          })
        },

      //Guardar el usuario
        saveTask(){
          const data = {
            id: this.task.id,
            name: this.task.name,
            description: this.task.description,
            start_date: this.task.start_date,
            end_date: this.task.end_date,
            hour: this.task.hour,
            period: this.task.period,
            repeat: this.task.repeat,
            days: this.task.days,
            users: this.usersAssigned,
          };

          axios.post('/web/tasks', data).then(response => {
              this.closeFormModal();               
              showSuccessMessage('Tarea creado exitosamente!');
              this.$emit('reload-table');
            }).catch(error => {
              const errors = error.response.data.errors;
              showErrorGroupMessages(errors)
          });
        },

      //Funcion para actualizar
        updateTask() {
            const taskId = this.id;

            const data = {
              id: this.task.id,
              name: this.task.name,
              description: this.task.description,
              start_date: this.task.start_date,
              end_date: this.task.end_date,
              hour: this.task.hour,
              period: this.task.period,
              repeat: this.task.repeat,
              days: this.task.days,
              users: this.usersAssigned,
            };

            axios.put(`/web/tasks/${taskId}`, data).then(response => {
                this.closeFormModal();               
                showSuccessMessage('Tarea actualizada exitosamente!');
                this.$emit('reload-table');
              })
            .catch(error => {
                const errors = error.response.data.errors;
                showErrorGroupMessages(errors)
            });
        },
        
      //Funcion para eliminar
        deleteTask() {
            axios.delete(`/web/tasks/${this.id}`,{
                headers: {
                    Authorization: `Bearer ${this.token}`, // Include the token in the headers
                },
            })
            .then(() => {
                showSuccessMessage('!Tarea eliminada exitosamente!');
                this.$emit('reload-table');
            }).catch(error => {
                const errors = error.response.data.errors;
                showErrorGroupMessages(errors)
            });
        },

      //Funcion para seleccionar todos los días
        setAllDays(value){
          this.task.days['Monday'] = value;
          this.task.days['Tuesday'] = value;
          this.task.days['Wednesday'] = value;
          this.task.days['Thursday'] = value;
          this.task.days['Friday'] = value;
          this.task.days['Saturday'] = value;
          this.task.days['Sunday'] = value;
        },

      //Agregar usuarios al array de seleccionados
        addUser(user) {
          //Comprobar que no este en el usuario ya asignado en el array 
          const existInArray = this.usersAssigned.map(u => u.id == user.id); 

          if (!existInArray[0]) {
            this.usersAssigned.push(user);
          }
          
          this.inputSearch = null;
        },

      //Remover usuarios del array de seleccionados
        removeUser(user) {
          this.usersAssigned = this.usersAssigned.filter(u => u.id !== user.id);
        },
   },
   watch: {
    inputSearch: debounce(function () {
      this.getUsers();
    }, 300),
   },
   created() {
    this.getUsers();
   },
   mounted() {
    initFlowbite();
   }
 };
 </script>

<style scoped>
  .autocomplete-results {
    list-style-type: none;
    padding: 0;
    background-color: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
  }

  .autocomplete-results li {
    cursor: pointer;
    padding: 8px;
  }

  ul {
    padding: 0;
    list-style-type: none;
  }

  ul li {
    margin-bottom: 5px;
  }
</style>