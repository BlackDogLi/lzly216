<template>
	<el-container>
		<el-row style="width: 100%">
			<el-col class="shadow">
				<el-form ref="myForm" :model="myForm" v-loading="editFormLoading" label-width="100px">
					<el-form-item v-for="set in sets" :label="set.set_title" :key="set.id">
						<el-input :type="set.data_type" v-model="myForm[set.set_name]"></el-input>
					</el-form-item>
					<el-form-item>
						<el-button @click="closeForm('myForm')">取 消</el-button>
						<el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
					</el-form-item>
				</el-form>
			</el-col>
		</el-row>
	</el-container>
</template>
<script type="text/ecmascript-6">
	export default {
		data () {
			return {
				editFormLoading: false,
				sets: [],
				myForm: {}
			}
		},
		created () {
			this.getData();
		},
		methods: {
			getData: function () {
				let $_this = this;
				$_this.editFormLoading = true;
				$_this.axios.get('/setting').then(function (response) {
					let res = response.data;
					if (res != false) {
						if (res.length > 0) {
							for (var index in res ) {
								$_this.myForm[res[index].set_name] = res[index].set_value;
							}
							$_this.sets = res;
						}
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					$_this.editFormLoading = false;
				}).catch(function (error) {
					console.log(error);
					$_this.editFormLoading = false;
				});
			},
			submitMyForm: function (myForm) {
				let $_this = this;
				$_this.axios.put('/setting/update', $_this.myForm).then(function (response) {
					let res = response.data;
					$_this.$message({
						message: res.status == 'success' ? '更新成功' : '更新失败',
						type: res.status
					});
				}).catch(function (error) {
					console.log(error);
				});
			}
		}
	}
</script>