<template>
    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Titulo del dashboard-->
        <!-- <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Nuevas Incidencias</h1>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Contenido de la pagina -->
        <div class="content">
            <div class="container-fluid">
                <h1> Bienvenido </h1>
            </div>
        </div> <!--  Fin del conetnido -->
    </div> <!-- Fin del contenido principal -->
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import { useToastr } from '../toastr.js';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

export default {
  components: {
  },
  data() {
    return {
      //Objeto para la edicion
      equipment: {
        name: '',
      },
      //Modales y titulos
      id: 0,
      update: false,
      //Variables para comprobar los resultados y el total de los datos
      equipments: [],
      companies: [],
      delegations: [],
      selectDelegations: [],
      totalEquipments: 0,

      //Filtros para el listado
      nameSearch: null,
      domainSearch: null,
      serialNumberSearch: null,
      addressSearch: null,
      installDateSearch: null,
      companySearch: null,
      delegationSearch: null,
      observationsSearch: null,
      selectedCompany: 0, //Inicializacion del select
      selectedCompanySearch: 0, //Inicializacion del select
      paginationNumber: 10,
    };
  },
  methods: {
    //Funcion para guardar
    saveEquipment(){
        const data = {
            name: this.equipment.name,
        };

        axios.post('/web/equipments', data).then(response => {
            $('#equipmentFormModal').modal('hide');
            useToastr().success('¡Equipo creado exitosamente!');

            this.getEquipments();
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

  }
};
</script>