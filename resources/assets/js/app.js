import Vue from 'vue'
import { BootstrapVue, ModalPlugin } from 'bootstrap-vue'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('articles', require('./components/login.vue'));
Vue.component('dashboard', require('./components/dashboard.vue'));
Vue.component('role', require('./components/role.vue'));
Vue.component('user', require('./components/user.vue'));
Vue.component('expensecategory', require('./components/expense_category.vue'));
Vue.component('expenses', require('./components/expenses.vue'));

Vue.use(BootstrapVue)
Vue.use(ModalPlugin)


const app = new Vue({
    el: '#app'
});
