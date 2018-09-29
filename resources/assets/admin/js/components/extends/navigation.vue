<template>
	<!--<el-container>
        <el-row :gutter="20">

        </el-row>
    </el-container>-->
	<el-container>
		<el-row :gutter="20">
			<el-col :span="9">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>添加名称</span>
                    </div>
                    <el-form ref="myForm" :rules="myRules" :model="myForm">
                        <el-form-item label="菜单名称" prop="name">
                            <el-input v-model="myForm.name"></el-input>
                        </el-form-item>
                        <el-form-item label="菜单地址" prop="url">
                            <el-input v-model="myForm.url"></el-input>
                        </el-form-item>
                        <el-form-item label="排序">
                            <el-input v-model="myForm.sorting"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit('myForm')">确定</el-button>
                            <el-button @click="onReset('myForm')">取消</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
                <!--<el-collapse v-model="activeMenuAddCollapse">
                    <el-collapse-item title="添加导航" name="1" style="padding-left:10px; padding-right:15px;">
                        <el-form ref="myForm" :rules="myRules" :model="myForm">
                            <el-form-item label="菜单名称" prop="name">
                                <el-input size="small" v-model="myForm.name"></el-input>
                            </el-form-item>
                            <el-form-item label="菜单地址" prop="url">
                                <el-input size="small" v-model="myForm.url"></el-input>
                            </el-form-item>
                            <el-form-item label="排序">
                                <el-input size="small" v-model="myForm.sorting"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit('myForm')">确定</el-button>
                                <el-button @click="onReset('myForm')">取消</el-button>
                            </el-form-item>
                        </el-form>
                    </el-collapse-item>
                </el-collapse>-->
			</el-col>
			<el-col :span="15">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>菜单列表</span>
                        <el-button icon="el-icon-edit" @click="updateMenu" size="small" type="primary pull-right">保存</el-button>
                    </div>
                    <el-table :data="navigations" :default-sort="{prop: 'sorting', order: 'descending'}">
                        <el-table-column label="排序" width="80">
                            <template slot-scope="scope">
                                <el-input size="mini" v-model="sortList[scope.row.name]" @change="inputSort" placeholder="请输入内容"></el-input>
                            </template>
                        </el-table-column>
                        <el-table-column prop="name" label="菜单名称" min-width="150"></el-table-column>
                        <el-table-column prop="url" label="地址" min-width="150"></el-table-column>
                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-button ></el-button>
                                <el-button type="danger" size="small" icon="el-icon-delete" @click="removeItem(row)"></el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
			</el-col>
		</el-row>
    </el-container>
</template>
<script type="text/ecmascript-6">
	export default {
		data () {
			return {
				myForm: {
					name: '',
					url: '',
					sorting: 0
				},
				myRules: {
					name: [
						{required: true, type: "string", message: '请填写菜单名称', trigger: 'blur'},
						{min: 1, message: '长度大于1个字符', trigger: 'blur'}
					],
					url: [
						{required: true, type: "string", message: '请填写url地址', trigger: 'blur'},
						{min:1, message: '长度大于1个字符', trigger: 'blur'}
					]
				},
				navigations: [],
				sortList: {},
				activeMenuAddCollapse: '1',
				listLoading: false,
				editLoading: false,
				showHeader: false,
			}
		},
		methods: {
			onSubmit: function (myForm) {
				var _self = this;
				_self.$refs[myForm].validate((valid) => {
					if (!valid) {
						console.log('myForm valid error.');
						return false;
					}
					_self.navigations.splice(-1, 0, this.myForm);
					_self.myForm = {
						name: '',
						url: '',
						sorting: 0
					};
				});
			},
			onReset: function (myForm){
				this.myForm = {
					name: '',
					url: '',
					sorting: 0
				};
			},
			getData: function () {
				let _self = this;
				_self.listLoading = true;
				_self.axios.get('/navigations').then(function (response) {
					let res = response.data;
					if (res != false) {
						res.sort(function (x, y) {
							return x.sorting > y.sorting ? 1 : -1;
						});
						_self.navigations = res;
						//获取排序值
						if (_self.navigations.length > 0) {
							for (let index in _self.navigations) {
								_self.sortList[_self.navigations[index]['name']] = _self.navigations[index]['sorting'];
							}
						}
					} else {
						console.log('数据获取失败或者数据为空');
					}
					_self.listLoading = false;
				}).catch(function (error) {
					_self.listLoading = false;
				});
			},
			removeItem: function (item) {
				var _self = this;
				_self.$confirm('确认删除该记录吗?', '提示').then(() => {
					for (let index in _self.navigations) {
						_self.navigations.splice(index, 1);
					}
				}).catch(() => {
					_self.listLoading = false;
				});
			},
			inputSort: function (value) {
				for (let key in this.sortList) {
					for (let index in this.navigations){
						if (this.navigations[index].name == key) {
							this.navigations[index]['sorting'] = parseInt(this.sortList[key]);
						}
					}
				}
				this.navigations.sort(function (x, y) {
					return x.sorting > y.sorting ? 1 : -1;
				});
			},
			updateSort: function () {
				if (this.navigations.length > 0) {
					this.navigations.sort(function (x, y) {
						return x.b > y.b ? 1 : -1;
					});
				}
				console.log(this.navigations);
			},
			updateMenu: function () {
				let _self =this;
				_self.editLoading = true;
				_self.axios.put('/navigations/update', _self.navigations).then(function (response) {
					let res = response.data;
					if (res != false) {
						_self.$mesaage({
							message: '更新成功',
							type: 'success',
							duration: 3 * 1000
						});
					} else {
						_self.$message({
							message: '更新失败',
							type: 'error',
							duration: 3 * 1000
						});
					}
					_self.editLoading = false;
				}).catch(function (error) {
					console.log(error);
					_self.editLoading = false;
				});
			},
			orderBy: function (name) {
				return function (o, p) {
					var a, b;
					if (typeof o === "object" && typeof p === "object" && o && p) {
						a = o[name];
						b = p[name];
						if (a === b) {
							return 0;
						}
						if (typeof a === typeof b) {
							return a < b ? 1 : -1;
						}
						return typeof a < typeof b ? -1 : 1;
					} else {
						throw("error");
					}
				}
			}
		},
		mounted () {
			this.getData();
		}
	}
</script>