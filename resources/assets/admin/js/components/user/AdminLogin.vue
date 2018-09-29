<template>
    <el-container v-loading="loading">
        <el-row class="login-form">
            <el-col :span = "24">
                <el-form ref="myForm" :model="myForm" :rules ="myRules" label-width = "100px;">
                    <el-form-item label = "用户名：" prop = "email">
                        <el-input type = "email" v-model = "myForm.email" placeholder = "请输入用户名"></el-input>
                    </el-form-item>
                    <el-form-item label = "密码：" prop = "password">
                        <el-input type = "password" v-model = "myForm.password" placeholder = "请输入密码"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type = "primary" @click = "loginSubmit('myForm')">登录</el-button>
                        <el-button @click = "resetForm('myForm')">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-container>
</template>
<script type = "text/ecmascript-6">
		export default {
			data() {
				return {
					loading: false,
					myForm: {
						email: '',
						password: ''
					},
					myRules: {
						email: [
							{required: true, type: 'email', message: '请填写用户名', trigger: 'blur'}
						],
						password: [
							{required: true, message: '请填写密码', tigger: 'blur'},
							{min: 6, max: 64, message: '密码长度在6到64个字符', tigger: 'blur'}
						]
					}
				}
			},
			methods: {
				loginSubmit: function (myForm) {
					var _self = this;
					var $_duration = 2 * 1000;
					_self.$refs[myForm].validate((valid) => {
						if (valid) {
							_self.loading = true;
							_self.axios.post('/login/login', _self.myForm).then(function (response) {
								let data = response.data;
								if (data.status == 200) {
									sessionStorage.setItem('lzly', JSON.stringify(data.user));
									_self.$message ({
										message: data.info,
										type: 'success',
										duration: $_duration
									});
									setTimeout (function () {
										_self.$router.push({path: '/admin'})
									}, $_duration);
								} else {
									_self.$message.error(data.info);
									_self.loading = false;
								}
							}).catch( function (error) {
								_self.loading = false;
								console.log(error);
							});
						} else {
							console.log('myForm valid error.');
							return false;
						}
					});
				},
				resetForm: function (myForm) {
					this.$refs[myForm].resetFields();
				}
			}
		}
</script>