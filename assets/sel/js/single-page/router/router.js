import Vue from "vue";
import VueRouter from "vue-router";
import Archivo from "@/components/Archivos/Archivos";
import Enviar from "@/components/Archivos/Enviar";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/sel/admin/archivo",
            name: "Archivo",
            component: Archivo
        },
        {
            path: "/sel/admin/archivo/enviar",
            name: "Enviar",
            component: Enviar
        }
    ]
});
