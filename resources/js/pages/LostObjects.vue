<template>
    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Titulo del dashboard-->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Objetos Perdidos <i class="fa fa-box-open"></i></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido de la pagina -->
        <div class="content">
            <div class="container-fluid">
                <div class="card-body">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="register">Registrador</label>
                                    <input v-model="lostObject.register" type="text" class="form-control" id="register" placeholder="Registrador">
                                </div>
                                <div class="col-md-2">
                                    <label for="date">Fecha</label>
                                    <input v-model="lostObject.date" type="text" class="form-control dateFlap" id="date">
                                </div>
                                <div class="col-md-4">
                                    <label for="delivered_by">Entregado Por</label>
                                    <input v-model="lostObject.delivered_by" type="text" class="form-control" id="delivered_by" placeholder="Entregado Por">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="bus">Bus</label>
                                    <input v-model="lostObject.bus" type="text" class="form-control" id="bus" placeholder="Bus">
                                </div>
                                <div class="col-md-3">
                                    <label for="line">Linea</label>
                                    <input v-model="lostObject.line" type="text" class="form-control" id="line" placeholder="Linea">
                                </div>
                                <div class="col-md-6">
                                    <label for="driver">Conductor</label>
                                    <input v-model="lostObject.driver" type="text" class="form-control" id="driver" placeholder="Conductor">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="reception_by">Recibido Por</label>
                                    <input v-model="lostObject.reception_by" type="text" class="form-control" id="reception_by" placeholder="Recibido Por">
                                </div>
                                <div class="col-md-2">
                                    <label for="reception_date">Fecha de entrega</label>
                                    <input v-model="lostObject.reception_date" type="text" class="form-control dateFlap" id="date">
                                </div>
                                <div class="col-md-4">
                                    <label for="destination">Destino</label>
                                    <input v-model="lostObject.destination" type="text" class="form-control" id="destination" placeholder="Destino">
                                </div>
                                <div class="col-md-2">
                                    <label for="destination_date">Fecha entrega en destino</label>
                                    <input v-model="lostObject.destination_date" type="text" class="form-control dateFlap" id="date">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="description">Descripcion</label>
                                    <textarea v-model="lostObject.description" type="text" class="form-control" id="description" placeholder="Descripciones de la incidencia." ></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">     
                                    <div class="modal-body">
                                        <button @click="saveIssue" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import { useToastr } from '../toastr.js';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

export default {

  data() {
    return {
      //Objeto para la edicion
      lostObject: {
        code:'',
        register:'',
        date:'',
        bus:'',
        line:'',
        driver:'',
        description:'',
        delivered_by:'',
        reception_by:'',
        reception_date:'',
        destination:'',
        destination_date:''
      },  
    };
  },
  methods: {
    clearForm(){
        this.lostObject.code = '',
        this.lostObject.register = '',
        this.lostObject.date = '',
        this.lostObject.bus = '',
        this.lostObject.line = '',
        this.lostObject.driver = '',
        this.lostObject.description = '',
        this.lostObject.delivered_by = '',
        this.lostObject.reception_by = '',
        this.lostObject.reception_date = '',
        this.lostObject.destination = '',
        this.lostObject.destination_date = ''
    },

    //Funcion para guardar
    saveIssue(){
        const data = {
            code: this.lostObject.code,
            register: this.lostObject.register,
            date: this.lostObject.date,
            bus: this.lostObject.bus,
            line: this.lostObject.line,
            driver: this.lostObject.driver,
            description: this.lostObject.description,
            delivered_by: this.lostObject.delivered_by,
            reception_by: this.lostObject.reception_by,
            reception_date: this.lostObject.reception_date,
            destination: this.lostObject.destination,
            destination_date: this.lostObject.destination_date,
        };

        axios.post('/web/lost_objects', data).then(response => {
            this.clearForm();
            useToastr().success('Objecto perdido creado exitosamente!');
        }).catch(error => {
            const errors = error.response.data.errors;
            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const errorMessages = errors[key];
                    for (const errorMessage of errorMessages) {
                        useToastr().error(errorMessage);
                    }
                }
            }
        });
    },
  },
  watch: {
  },
  created() {
  },
  mounted() {
    flatpickr(".dateFlap", {
        enableTime: false,
        dateFormat: "Y-m-d",
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
            shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            },
            months: {
            shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
            longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            },
        },
    });

    flatpickr(".timeFlap", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
  }
};
</script>