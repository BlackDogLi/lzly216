<template>
	<el-row class="panel">
		<!-- 后台top -->
		<el-col :span="24" class="panel-top">
			<el-col :span="20" style="font-size:26px;">
				<span><i style="font-style:normal;">Lzly216-nest</i></span>
			</el-col>
			<el-col :span="4" class="rightbar">
				<el-dropdown trigger="click">
					<span class="el-dropdown-link pit-username">
						<img :src="this.sysUserAvater" class="head" onerror="javascript:this.src='/admin/image/logo.png'"/>
						{{sysUserName}}
					</span>
					<el-dropdown-menu slot="dropdown" class="pit-user-dropdown">
						<el-dropdown-item @click.native="gouser">设置</el-dropdown-item>
						<el-dropdown-item divided @click.native="logout">退出登录</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
			</el-col>
		</el-col>

		<!-- 主题内容 -->
		<el-col :span="24" class="panel-center">
			<!-- 左侧边栏 -->
			<aside style="width: 230px;">
				<el-menu :default-active="currentPath" class="el-menu-vertical-demo" @open="handleopen" @close="handleclose" @select="handleselect" unique-opened router theme="dark">
					<template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
						<el-submenu :index = "index + ''" v-if="!item.leaf">
							<template slot="title"><i :class="item.iconCls"></i>{{item.name}}</template>
							<el-menu-item v-for = " child in item.children " :index = "child.path">{{child.name}}</el-menu-item>
						</el-submenu>
						<el-menu-item v-if = "item.leaf && item.children.length>0" :index = "item.children[0].path">
							<i :class="item.iconCls"></i>{{item.children[0].name}}
						</el-menu-item>
					</template>
				</el-menu>
			</aside>
			<!-- 右侧内容 -->
			<section class="panel-c-c">
				<div class="grid-content bg-purple-light">
					<el-col :span="24" style="margin-bottom:15px;">
						<span class="pit-current-route">{{currentPathName}}</span>
						<el-breadcrumb separator="/" style="float:right;">
							<el-breadcrumb-item :to="{ path : '/admin'}">首页</el-breadcrumb-item>
							<el-breadcrumb-item v-if="currentPathNameParent!=''">{{currentPathNameParent}}</el-breadcrumb-item>
							<el-breadcrumb-item v-if="currentPathName!=''">{{currentPathName}}</el-breadcrumb-item>
						</el-breadcrumb>
					</el-col>
					<el-col :span="24" class="pit-main">
						<router-view></router-view>
					</el-col>
				</div>
			</section>
		</el-col>
	</el-row>
</template>
<script type="text/ecmascript-6">
	export default {
		data () {
			return {
				currentPath: '/admin',
				currentPathName: '数据中心',
				currentPathNameParent: '首页',
				sysUserName: '',
				sysUserAvater: '',
				form: {
					name: '',
					region: '',
					date1: '',
					date2: '',
					delivery: false,
					type: [],
					resource: '',
					desc: ''
				}
			}
		},
		watch: {
			'$route' (to, from) {
				this.currentPath = to.path;
				this.currentPathName = to.name;
				this.currentPathNameParent = to.matched[0].name;
			}
		},
		methods: {
			onSubmit() {
				console.log('submit');
			},
			handleopen() {},
			handleclose() {},
			handleselect: function (a, b) {},
			logout: function () {},
			gouser: function () {this.$router.push({path: 'user'});}
		},
		mounted() {
			this.currentPath = this.$route.path;
			this.currentPathName = this.$route.name;
			this.currentPathNameParent = this.$route.matched[0].name;

			var user = sessionStorage.getItem('lzly');
			if (user) {
				user = JSON.parse(user);
				this.sysUserName = user.name || '';
				this.sysUserAvater = user.avatar || '';
			}
		}
	}
</script>
<style type="text/css" scoped>
.fade-enter-active,
.fade-leave-active {
	transition: opacity .5s
}

.fade-enter,
.fade-leave-active {
	opacity: 0
}

.panel {
	position: absolute;
	top: 0px;
	bottom: 0px;
	width: 100%;
}

.panel-top {
	height: 60px;
	line-height: 60px;
	background: #1F2D3D;
	color: #c0ccda;
}

.panel-top .rightbar {
	text-align: right;
	padding-right: 35px;
}

.panel-top .rightbar .head {
	width: 40px;
	height: 40px;
	border-radius: 20px;
	margin: 10px 0px 10px 10px;
	float: right;
}

.panel-center {
	background: #324057;
	position: absolute;
	top: 60px;
	bottom: 0px;
	overflow: hidden;
}

.panel-c-c {
	background: #f1f2f7;
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	left: 230px;
	overflow-y: scroll;
	padding: 20px;
}

.el-menu .fa {
	vertical-align: baseline;
	margin-right: 10px;
	font-size: 16px;
}

.logout {
	/*background: url(../assets/logout_36.png);*/
	background-size: contain;
	width: 20px;
	height: 20px;
	float: left;
}

.logo {
	width: 64px;
	height: 64px;
	float: left;
	margin-left:10px;
}

.tip-logout {
	float: right;
	margin-right: 20px;
	padding-top: 5px;
}

.tip-logout i {
	cursor: pointer;
}

.admin {
	color: #c0ccda;
	text-align: center;
}

.pit-main {
	background-color: #fff;
	box-sizing: border-box;
}

.pit-current-route {
	width: 200px;
	float: left;
	color: #475669;
	font-weight: bold;
}
.el-menu-item,.el-submenu {
	border-bottom: 1px #2a3952 solid;
}
.pit-username {
	color: #FFF;
	cursor: pointer;
}
.pit-user-dropdown {
	color: #0f0f0f;
	font-size: 14px;
}
</style>