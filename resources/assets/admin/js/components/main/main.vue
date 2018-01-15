<template>
	<div class="dashboard">
		<el-row :gutter="20">
			<el-col :span="12">
				<div class="grid-content bg-purple">
					<el-collapse v-model="statisticalNames">
						<el-collapse-item name="1">
							<template slot="title">源数据</template>
							<div class="collapse-content" v-loading="statisticalLoading">
								<ul>
									<li>
										<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
										文章:10篇
									</li>
									<li>
										<i class="fa fa-trash-o" aria-hidden="true"></i>
										删除文章：15篇
									</li>
									<li>
										<li class="fa fa-comments-o" aria-hidden="true"></li>
										评论：100条
									</li>
								</ul>
							</div>
						</el-collapse-item>
					</el-collapse>
				</div>
			</el-col>
			<el-col :span="12">
				<div class="grid-content bg-purple">
					<el-collapse v-model="recentNames">
						<el-collapse-item name="1">
							<template slot="title">最近发布</template>
							<div class="collapse-content" v-loading="recentLoading">
								<div class="posts-list">
									<ul>
										<li>
											<span class="posts-date">2018-1-11</span>
											<router-link :to="{ path: '/'}">欢迎你!</router-link>
										</li>
									</ul>
								</div>
							</div>
						</el-collapse-item>
					</el-collapse>
				</div>
			</el-col>
		</el-row>
	</div>
</template>
<style type="text/css">
	.dashboard {
		padding: 1em;
	}
	.dashbooard ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.collapse-content {
		display: block;
		overflow: hidden;
	}
	.posts-list .posts-date {
		float: right;
	}
	.posts-list li a {
		text-decoration: none;
	}
	.posts-list li {
		padding: 5px 0;
		border-bottom: 1px #eee dashed;
	}
</style>
<script type="text/ecmascript-6">
export default {
	data () {
		return {
			statisticalLoading: false,
			recentLoading: false,
			statisticalNames: ['1','2'],
			recentNames: ['1'],
			statistical: {
				posts: 0,
				comments: 0,
				post_trash: 0,
				recent_posts:[]
			}
		}
	},
	created () {
		this.getMeta();
	},
	methods: {
		getMeta: function () {
			let $_this = this;
			$_this.statisticalLoading = true;
			$_this.axios.get('/admin/lately').then(function (response) {
				let res = response.data;
				if (res != false) {
					$_this.statistical = res;
				} else {
					$_this.$message({
						message: '数据获取失败',
						type: 'error'
					});
				}
				$_this.statisticalLoading = false;
			}).catch(function (error) {
				console.log(error);
				$_this.statisticalLoading = false;
			});
		},
	},

}
</script>