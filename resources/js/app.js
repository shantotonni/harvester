require('./bootstrap');

import Vue from 'vue';
import App from './views/App'

//import router
import router from "./router/index";

//import filter
import {filter} from './filter';

//import vuex
import Vuex from 'vuex';
Vue.use(Vuex);
import storeData from './store/index';
const store = new Vuex.Store(storeData);

//import editor
import Vue2Editor from "vue2-editor";
Vue.use(Vue2Editor);

//import v-form
import { Form } from 'vform'
window.Form = Form;

//import sweetalert
import Swal from 'sweetalert2';
window.Swal = Swal;

// main origin
Vue.prototype.mainOrigin = 'http://localhost:8080/harvester/'

axios.defaults.baseURL = window.location.origin + '/harvester/';

import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout: 5000})

export const bus = new Vue();

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('skeleton-loader', require('./components/loaders/Straight').default);
Vue.component('datatable', require('./components/datatable/Index').default);
Vue.component('data-export', require('./components/datatable/Export').default);
Vue.component('breadcrumb', require('./components/layouts/Breadcrumb').default);
Vue.component('menu-tree-view', require('./components/menu-permission/Index').default);
Vue.component('barChart', require('./components/chart/BarChart').default);

//user global action component
Vue.component('user-list-action', require('./components/action/user/Action').default);
Vue.component('user-list-status', require('./components/action/user/Status').default);

//custom pagination component
Vue.component('pagination', require('./components/partial/PaginationComponent.vue').default);


new Vue({
    el: '#app',
    components: {App},
    router,
    store
});
