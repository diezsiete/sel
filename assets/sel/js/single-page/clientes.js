import Vue from 'vue';

import 'vuetify/dist/vuetify.min.css'
import './../../css/single-page.scss';

//import '@progress/kendo-theme-default/dist/all.css'
//import { DropDownList } from '@progress/kendo-vue-dropdowns';

//import ListadoNomina from './components/Clientes/ListadoNomina';
import ListadoNomina2 from "@/components/Clientes/ListadoNomina2";
import Vuetify from "vuetify";



//Vue.component('dropdownlist', DropDownList);
Vue.component('listado-nomina2', ListadoNomina2);
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