import Vue from "vue";
import VueRouter from "vue-router";
import Home from "@clientes/pages/Home";
import ListadoNominaResumen from "@clientes/pages/ListadoNomina/Resumen"
import TrabajadorActivoDetalle from "@clientes/components/TrabajadorActivoDetalle";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/sel/clientes",
            name: "Home",
            component: Home
        },
        {
            path: "/sel/clientes/listado-nomina/:convenio/:fecha",
            name: "ListadoNominaResumen",
            component: ListadoNominaResumen
        },
        {
            path: "/sel/clientes/empleado/:empleadoId",
            name: "TrabajadorActivoDetalle",
            component: TrabajadorActivoDetalle
        }
    ]
});
