import Vue from 'vue';
import VueRouter from 'vue-router';
import cvRoutes from './cv';
import estudioRoutes from './estudio'

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        cvRoutes,
        estudioRoutes
    ]
});