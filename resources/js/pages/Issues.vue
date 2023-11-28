<template>
    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Titulo del dashboard-->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Incidencias <i class="fas fa-exclamation-triangle"></i></h1>
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
                                <div class="col-md-2">
                                    <label for="date">Fecha</label>
                                    <input v-model="issue.date" type="text" class="form-control dateFlap" id="date">
                                </div>
                                <div class="col-md-5">
                                    <label for="company">Empresa</label>
                                    <input v-model="issue.company" type="text" class="form-control" id="company" placeholder="Empresa">
                                </div>
                                <div class="col-md-5">
                                    <label for="category">Categoria</label>
                                    <input v-model="issue.category" type="text" class="form-control" id="category" placeholder="Categoria">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="bus">Bus</label>
                                    <input v-model="issue.bus" type="text" class="form-control" id="bus" placeholder="Bus">
                                </div>
                                <div class="col-md-3">
                                    <label for="line">Linea</label>
                                    <input v-model="issue.line" type="text" class="form-control" id="line" placeholder="Linea">
                                </div>
                                <div class="col-md-3">
                                    <label for="driver">Conductor</label>
                                    <input v-model="issue.driver" type="text" class="form-control" id="driver" placeholder="Conductor">
                                </div>
                                <div class="col-md-3">
                                    <label for="employee">Empleado</label>
                                    <input v-model="issue.employee" type="text" class="form-control" id="employee" placeholder="Empleado">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="hour">Hora</label>
                                    <input v-model="issue.hour" type="text" class="form-control timeFlap" id="date">
                                </div>
                                <div class="col-md-4">
                                    <label for="action">Acción Realizada</label>
                                    <input v-model="issue.action" type="text" class="form-control" id="action" placeholder="Acción Realizada">
                                </div>
                                <div class="col-md-2">
                                    <label for="notice_time">Hora del aviso</label>
                                    <input v-model="issue.notice_time" type="text" class="form-control timeFlap" id="notice_time">
                                </div>
                                <div class="col-md-2">
                                    <label for="change_bus">Cambio de BUS</label>
                                    <input v-model="issue.change_bus" type="text" class="form-control" id="change_bus" placeholder="Cambio de BUS">
                                </div>
                                <div class="col-md-2">
                                    <label for="solution_time">Hora de la solución</label>
                                    <input v-model="issue.solution_time" type="text" class="form-control timeFlap" id="solution_time">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="description">Descripcion</label>
                                    <textarea v-model="issue.description" type="text" class="form-control" id="description" placeholder="Descripciones de la incidencia." ></textarea>
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
      issue: {
        date: '',
        company: '',
        category: '',
        bus: '',
        line: '',
        driver: '',
        employee: '',
        hour: '',
        action: '',
        notice_time: '',
        change_bus: '',
        solution_time: '',
        description: '',
      },  
    };
  },
  methods: {
    clearForm(){
        this.issue.date = '',
        this.issue.company = '',
        this.issue.category = '',
        this.issue.bus = '',
        this.issue.line = '',
        this.issue.driver = '',
        this.issue.employee = '',
        this.issue.hour = '',
        this.issue.action = '',
        this.issue.notice_time = '',
        this.issue.change_bus = '',
        this.issue.solution_time = '',
        this.issue.description = ''
    },

    //Funcion para guardar
    saveIssue(){
        const data = {
            date: this.issue.date,
            company: this.issue.company,
            category: this.issue.category,
            bus: this.issue.bus,
            line: this.issue.line,
            driver: this.issue.driver,
            employee: this.issue.employee,
            hour: this.issue.hour,
            action: this.issue.action,
            notice_time: this.issue.notice_time,
            change_bus: this.issue.change_bus,
            solution_time: this.issue.solution_time,
            description: this.issue.description,
        };

        axios.post('/web/issues', data).then(response => {
            this.clearForm();
            useToastr().success('¡Incidencia creada exitosamente!');
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