<template>
    <el-container>

        <!-- 头部Start -->
        <el-header>
            <el-row :span="24" :gutter="10" type="flex" justify="space-between">
                <el-col :span="4">
                    <div class="grid-content"></div><span><h1>lzly-Blog</h1></span>
                </el-col>
                <el-col :span="16"></el-col>
                <el-col :span="4" class="rightbar">
                    <el-dropdown trigger="click">
                        <span class="el-dropdown-link">
                            <img :src="this.sysUserAvater" class="head" onerror="javascript:this.src='/admin/image/logo.png'"/>
                            {{sysUserName}}
                        </span>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item @click.native="gouser">设置</el-dropdown-item>
                            <el-dropdown-item divided @click.native="logout">退出登录</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-col>
            </el-row>
        </el-header>
        <el-container>

            <!-- 左侧Start -->
            <el-aside>
                <el-col>
                    <aside>
                        <el-menu :default-active="currentPath" class="el-menu-vertical-demo" @open="handleopen" @close="handleclose" @select="handleselect" text-color="#7a8599" active-text-color="#399ff4" unique-opened router>
                            <template v-for="(item,index) in $router.options.routes" v-if="!item.hidden">
                                <el-submenu :index = "index + ''" v-if="!item.leaf">
                                    <template slot="title"><i :class="item.iconCls"></i>{{item.name}}</template>
                                    <el-menu-item v-for = " child in item.children " :index = "child.path">{{child.name}}</el-menu-item>
                                </el-submenu>
                                <el-menu-item v-if = "item.leaf && item.children.length>0" :index = "item.children[0].path">
                                    <i :class="item.iconCls"></i><span slot="title">{{item.children[0].name}}</span>
                                </el-menu-item>
                            </template>
                        </el-menu>
                    </aside>
                </el-col>
            </el-aside>

            <el-container>

                <!-- 右侧Start -->
                <el-main>
                    <el-row>
                        <el-col :span="24" class="current">
                            <span class="pit-current-route">{{currentPathName}}</span>
                            <el-breadcrumb separator="/" style="float:right;">
                                <el-breadcrumb-item :to="{ path : '/admin'}">首页</el-breadcrumb-item>
                                <el-breadcrumb-item v-if="currentPathNameParent!=''">{{currentPathNameParent}}</el-breadcrumb-item>
                                <el-breadcrumb-item v-if="currentPathName!=''">{{currentPathName}}</el-breadcrumb-item>
                            </el-breadcrumb>
                        </el-col>
                    </el-row>


                        <el-col :span="24">
                            <router-view></router-view>
                        </el-col>

                </el-main>

                <!-- 底部Start -->
                <el-footer>
                    <el-col :span="24">
                        <span>联系方式:lzly216@163.com</span>
                        <span>版权归@山人所有</span>
                    </el-col>
                </el-footer>
            </el-container>
        </el-container>
    </el-container>
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
			logout: function () {
			    var $_this = this;
			    var _duration = 2*1000;
			    this.$confirm('确认退出么?', '提示',{

                }).then(() => {
                    $_this.axios.post('login/logout').then(function(response){
                        let data = response.data;
                        if (data.status == 200) {
                            sessionStorage.removeItem('lzly');
                            $_this.$message({
                                message: data.info,
                                type: 'success',
                                duration: _duration
                            });
                            setTimeout(function () {
                                $_this.$router.replace('/login');
                            }, _duration);
                        } else {
                            $_this.$message.error("退出失败");
                        }
                    }).catch(function (error) {
                        $_this.$message.error("退出失败");
                    });
                }).catch(function () {
                    $_this.$message.error("退出失败");
                    console.log(error);
                });
            },
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

.el-header .rightbar .head {
	width: 40px;
	height: 40px;
	border-radius: 20px;
	margin: 10px 0px 10px 10px;
	float: right;
}

.panel-center ul {
	padding: 5px;
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


.pit-current-route {
	width: 200px;
	float: left;
	color: #475669;
	font-weight: bold;
}

</style>