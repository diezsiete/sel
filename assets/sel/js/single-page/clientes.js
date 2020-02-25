import Vue from 'vue';

import 'vuetify/dist/vuetify.min.css'
import './../../css/single-page.scss';


import ListadoNomina from "@/components/Clientes/ListadoNomina";
import Vuetify from "vuetify";


Vue.component('listado-nomina', ListadoNomina);
Vue.use(Vuetify);
const vuetify = new Vuetify({});

new Vue({
    vuetify
    // data () {
    //     return {
    //         sports: ["Baseball", "Basketball", "Cricket", "Field Hockey", "Football", "Table Tennis", "Tennis", "Volleyball" ],
    //         msg: 'Hello Kendo Native DropDownList for Vue.js'
    //     }
    // }
}).$mount('#single-page-clientes');