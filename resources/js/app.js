window.Vue = require('vue');
require('./bootstrap');
import 'vue-js-modal/dist/styles.css'
import VModal from 'vue-js-modal'
Vue.use(VModal)

Vue.component('columns', require('./client/columns/columns.vue').default);

window.app  = new Vue({
    el: '#app',
})
