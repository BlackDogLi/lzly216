<template>
	<el-row>
        <el-col :span="24" class="shadow">
            <div class="main-content">
                <el-form :label-position="labelPosition" :rules="myRules" :model="myForm" v-loading="editFormLoading" label-width="100px" class="form6" >
                    <el-form-item label="新密码" style="width:60%" prop="password">
                        <el-input type="password" v-model="myForm.password"></el-input>
                    </el-form-item>
                    <el-form-item label="确认新密码" style="width:60%" prop="password_confirmation">
                        <el-input type="password" v-model="myForm.password_confirmation"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="colseForm('myForm')">取 消</el-button>
                        <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-col>
    </el-row>
</template>
<style type="text/css">
</style>
<script type="text/ecmascript-6">
	export default {
		data () {
			return {
			    labelPosition: 'left',
				editFormLoading: false,
				myForm: {
					password: '',
					password_confirmation: ''
				},
				myRules: {
					password: [
						{required: true, type: "string", message: '请填写新密码', trigger: 'blur'},
						{min: 6, max: 64, message: '长度在6到64个字符', trigger: 'blur'}
					],
				}
			}
		},
		create () {},
		methods: {
			submitMyForm: function (myForm) {
				let _self = this;
				_self.$refs[myForm].validate((valid) => {
					if (!valid) {
						console.log('myForm valid error.');
						return false;
					}
					_self.axios.post('/user/resetPassword', _self.myForm).then(function (response) {
						let res = response.data;
						_self.$message({
							message: res.status == 'success' ? '更新成功' : '信息更新失败',
							type: res.status
						});
						if (res.status == 'success') {
							_self.closeForm('myForm');
						}
						setTimeout (function () {
							sessionStorage.removeItem ('lzly');
							_self.$router.push({path: '/login'});
						}, 2*1000);
					}).catch(function () {
						if (error.response) {
							if (error.response.status == 422){
								for ( var index in error.response.data) {
									_self.$notify({
										title: '警告',
										message: error.response.data[index][0],
										type: 'warning'
									});
								}
							}
						} else {
							console.log(error);
						}
					});
				});
			},
			closeForm: function (myForm) {
				this.editFormVisible = false;
				this.$refs[myForm].resetFields();
				console.log('closeForm');
			},
			mounted () {}
		}
	}
</script>