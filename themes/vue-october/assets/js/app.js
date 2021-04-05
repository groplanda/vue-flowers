import Vue from 'vue';
import App from './App.vue';
import router from './router/routes.js';
import store from './store/index.js';
import Icon from '@vue/components/app/icon.vue';

Vue.component('icon', Icon);

new Vue({
  el: '#app',
  router,
  store,
  components: { App }
})
