require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import ToggleButton from 'vue-js-toggle-button'
import {routes} from './routes';
import storeData from './store';
import MainApp from './components/MainApp.vue';
import VModal from 'vue-js-modal';
import VueSlideToggle from 'vue-slide-toggle';
import  {BootstrapVue, BootstrapVueIcons}  from 'bootstrap-vue';
import Treeselect from "@riophae/vue-treeselect";



Vue.component('treeselect', Treeselect);
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(VModal)
Vue.use(ToggleButton)
Vue.use(VueSlideToggle)
Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(storeData)

const router = new VueRouter({
    routes,
    mode: 'history'
});




const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});
