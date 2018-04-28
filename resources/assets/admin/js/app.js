
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
//加载Ueditor
import '../../../../public/plug/UE/ueditor.config';
import '../../../../public/plug/UE/ueditor.all.min';
import '../../../../public/plug/UE/ueditor.parse.min';
import '../../../../public/plug/UE/lang/zh-cn/zh-cn';


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
import Login from './components/user/AdminLogin.vue';
import Menu from './components/main/menu.vue';
import Main from './components/main/main.vue';
import User from './components/user/user.vue';
import Setting from './components/set/setting.vue';
import Options from './components/set/options.vue';
import Articles from './components/article/articles.vue';
import Article from './components/article/article.vue';
import Categorys from './components/article/categorys.vue';
import Tags from './components/article/tags.vue';
import Navigations from './components/extends/navigation.vue';
import App from './AdminApp.vue';
/* router规则 */
const routes = [
    {
        path: '/login',
        component: Login,
        hidden: true
    },
	{
		path: '/',
		component: Menu,
		name: '仪表盘',
		iconCls: 'fa fa-home',
		leaf: true,
		children: [
			{ path: '/admin', component: Main, name: '仪表盘'}
		]
	},
	{
		path: '/',
		component: Menu,
		name: '文章',
		iconCls: 'fa fa-file-word-o',
		children: [
			{ path: '/articles', component: Articles, name: '文章管理'},
			{ path: '/articles/add', component: Article, name: '发布文章'},
			{ path: '/categorys', component: Categorys, name: '分类管理'},
			{ path: '/tags', component: Tags, name: '标签管理'}
		]
	},
	{
		path: '/',
		component:Menu,
		name: '',
		iconCls: 'fa fa-home',
		leaf: true,
		hidden: true,
		children:[
			{path: 'articles/edit/:id', component: Article, name: '编辑文章'}
		]

	},
	{
		path: '/',
		component: Menu,
		name: '扩展',
		iconCls: 'fa fa-external-link-square',
		children: [
			{ path: '/navigations', component:Navigations , name: '导航管理'}
			//{ path: 'options', component: User, name: '配置管理'}
		]
	},
	{
		path: '/',
		component: Menu,
		name: '设置',
		iconCls: 'fa fa-cog',
		children: [
			{path: '/options', component:Options, name: '配置项管理'},
			{ path: '/setting', component: Setting, name: '网站设置'}
		]
	},
	{
		path: '/',
		component: Menu,
		name: '',
		iconCls: 'fa fa-home',
		leaf: true,
		hidden: true,
		children: [
			{ path: '/user', component: User, name: '用户设置'}
		]
	}
];

/* 加载配置router */
const router = new VueRouter({
    history: true,
    root: 'admin',
    routes
});
/* 手动挂载 */
const app = new Vue({
    el: '#app',
    template: '<App/>',
    router,
    components: { App }
}).$mount('#app');
