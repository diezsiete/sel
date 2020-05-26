import Vue from "vue";
import VueRouter from "vue-router";
import DatosBasicos from "@/cv/pages/DatosBasicos";
import Estudios from "@/cv/pages/Estudios"

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/sel/cv",
            name: "DatosBasicos",
            component: DatosBasicos
        },
        {
            path: "/sel/cv/datos-basicos",
            name: "DatosBasicos",
            component: DatosBasicos
        },
        {
            path: "/sel/cv/estudios",
            name: "Estudios",
            component: Estudios
        }
    ]
});
