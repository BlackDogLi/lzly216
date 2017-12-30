<template>
    <div id = "app">
        <router-view></router-view>
    </div>
</template>
<script type = "text/ecmascript-6">
	export default{
		name: 'app',
		data () {
			return {
				user: JSON.parse(sessionStorage.getItem('lzly'))
			}
		},
		watch: {
			'$route'(to, from) {    //监听路由改变
				this.authLogin();
			}
		},
		methods: {
			authLogin: function () {
				let $_this = this;
				let user = JSON.parse(sessionStorage.getItem('lzly'));
				if (!user) {
					$_this.$router.push({path: 'login'});
				}
				$_this.axios.post('auth/check').then(function (response) {
					if (reponse.data.auth == 'Unauthenticated') {
						sessionStorage.removeItem('lzly');
						$_this.$router.push({path: 'login'});
					}
				}).catch(function (error) {
					console.log(error);
				});
			}
		},
		created: function () {
			this.authLogin();
		}
	}
</script>