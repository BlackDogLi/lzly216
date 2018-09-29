<template>
	<el-row :span="24" :gutter="30" >
		<el-col :span="12">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span>源数据</span>
				</div>
                <div class="text item">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                    文章:{{statistical.posts}}篇
                </div>
                <div class="text item">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                    删除文章：{{statistical.post_trash}}篇
                </div>
                <div class="text item">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    评论：{{statistical.comment}}条
                </div>
			</el-card>
		</el-col>
		<el-col :span="12">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>最近发布</span>
                </div>
                <div v-for="item in statistical.recent_posts" class="text item">
                    <router-link :to="{ path: '/'}">{{item.title}}</router-link>
                    <span style="float: right;">{{item.created_at}}</span>
                </div>
            </el-card>
		</el-col>
	</el-row>
</template>
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
			let _self = this;
			_self.statisticalLoading = true;
			_self.axios.get('/admin/lately').then(function (response) {
				let res = response.data;
				if (res != false) {
					_self.statistical = res;
				} else {
					_self.$message({
						message: '数据获取失败',
						type: 'error'
					});
				}
				_self.statisticalLoading = false;
			}).catch(function (error) {
				console.log(error);
				_self.statisticalLoading = false;
			});
		},
	},

}
</script>