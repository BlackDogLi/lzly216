
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('./App.vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* 导入扩展包 */
import Vue from 'vue/dist/vue.js';
import ElementUI from 'element-ui';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';



/* 加载 */
Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);


/**
 *  axios config
 *  We'll register a HTTP interceptor to attach the "CSRF" header to each of
 *  the outgoing requests issued by this application. The CSRF middleware
 *  included with Laravel will automatically verify the header's value.
 *  @type {string}
 */
Vue.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
};
Vue.axios.defaults.baseURL = Laravel.apiUrl;

/**
 * import custom/other function library
 */
import util from './lib/util';
import marked from 'marked';
import localforage from 'localforage';
Vue.prototype.util = util;
Vue.prototype.marked = marked;
Vue.prototype.localforage = localforage;


/* 导入vue单页 */
import App from './AdminApp.vue';
import Login from './components/user/AdminLogin.vue';
/* router规则 */
const routes = [
    {
        path: '/login',
        component: Login,
        hidden: true
    },
];

/* 加载配置router */
const router = new VueRouter({
    history: true,
    root: 'author',
    routes
});
/* 手动挂载 */
const app = new Vue({
    el: '#app',
    template: '<App/>',
    router,
    components: { App }
}).$mount('#app');
