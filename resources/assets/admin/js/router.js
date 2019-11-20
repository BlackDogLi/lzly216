import Vue from 'vue'
import VueRouter from 'vue-router'

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
import Imgposition from './components/image/adposition';
import CommentsList from './components/comments/list.vue';
import Imgs from './components/image/img';

/* router规则 */
const routers = [
    {
        path: '/login',
        component: Login,
        hidden: true
    },
    {
        path: '/',
        component: Menu,
        name: '',
        iconCls: 'fa fa-home',
        leaf: true,
        children: [
            { path: '/admin', component: Main, name: '概况'}
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
        name: '图片',
        iconCls: 'fa fa-file-image-o',
        children: [
            {path: '/img', component: Imgs, name: '上传图片'},
            {path: '/imgpositon', component: Imgposition, name: '广告位'},
        ]

    },
    {
        path: '/',
        component: Menu,
        name: '留言',
        iconCls: 'fa fa-comments-o',
        children: [
            {path: '/comments', component: CommentsList, name: '留言列表'}
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
]

export default routers