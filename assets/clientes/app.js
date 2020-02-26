import Vue from 'vue';
import router from "./router/router";
import store from "@clientes/store/store";
import App from './App.vue'
import vuetify from "./plugins/Vuetify";
import './style/single-page.scss';



const app = new Vue({
    render: h => h(App),
    store,
    router,
    vuetify
}).$mount('#app');