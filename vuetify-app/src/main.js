import Vue from 'vue';
import Vuelidate from 'vuelidate';
import App from './App.vue';
import vuetify from './plugins/vuetify';

import store from './store';
import router from './router';
import i18n from './i18n';

Vue.config.productionTip = false;

Vue.use(Vuelidate);

new Vue({
  vuetify,
  i18n,
  router,
  store,
  render: h => h(App)
}).$mount('#app');