<template>
    <div id = "app">
        <router-view></router-view>
    </div>
</template>
<script type = "text/ecmascript-6">
	export default{
		name: 'app',

		watch: {
			'$route'(to, from) {    //监听路由改变
				this.authLogin();
			}
		},
		created: function () {
			this.authLogin();
		}
	}
</script>