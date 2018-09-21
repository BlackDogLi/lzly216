<template>
	<el-row style="width:100%">
		<el-col :span="24">
			<el-button type="primary" @click="handleCreate" icon="el-icon-plus">新增</el-button>
		</el-col>

		<el-col :span="24">
			<tree-table
					:columns="columns"
					:tree-structure="true"
					:data-source="tableData"
					:props="defaultProps"
					@edit="handleEdit"
					@delete="handleDistory">
			</tree-table>
		</el-col>

		<el-col :span="24">
			<el-dialog :title="myFormTitle" v-model="editFormVisible" :visible.sync="editFormVisible">
				<el-form ref="myForm" :rules="myRules" label-width="80px;" :model="myForm">
					<el-form-item label="分类名称" prop="category_name">
						<el-input v-model="myForm.category_name" auto-complete="off"></el-input>
					</el-form-item>
					<el-form-item label="分类别名" prop="category_flag">
						<el-input v-model="myForm.category_flag" auto-complete="off"></el-input>
					</el-form-item>
					<el-form-item label="分类描述">
						<el-input type="textarea" v-model="myForm.category_description"></el-input>
					</el-form-item>
					<el-form-item label="父分类">
						<!--<el-select v-model="myForm.category_parent" placeholder="请选择父分类">
							<el-option v-for="item in categorys" :key="item.id" :label="item.category_name" :value="item.id"></el-option>
						</el-select>-->
						<el-cascader expand-trigger="hover" :options="categorys" :props="props1" v-model="parentids" change-on-select>

						</el-cascader>
					</el-form-item>
					<el-form-item v-if="myForm.id">
						<el-input v-model="myForm.id" style="display: none;"></el-input>
					</el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="closeForm('myForm')">取消</el-button>
					<el-button type="primary" @click="submitMyForm('myForm')">确定</el-button>
				</div>
			</el-dialog>
		</el-col>
	</el-row>
</template>
<script type="text/ecmascript-6">
	import treeTable from '../../../../common/components/tableTree';
	export default {
		data() {
			return {
                props1:{
			        'value': "id",
                    'label': 'category_name',
				},
                parentids:[],
				tableData: [],
				columns: [
					{
					    text: '分类名称',
						dataIndex: 'category_name'
					},
					{
					    text: '分类别名',
						dataIndex: 'category_flag'
					},
                    {
                        text: '分类描述',
                        dataIndex: 'category_description'
                    },
				],
				defaultProps: {
			        children: 'children',
					label:  'category_name'
				},
				categorys: [],
				myForm: {
					id: 0,
					category_name: '',
					category_flag: '',
					category_description: '',
					category_parent: 0,
				},
				myRules: {
					category_name: [
						{required: true, type: "string", message: '请填写分类名称', trigger: 'blur'}
					],
					category_flag: [
						{required: true, type: "string", message: '请填写分类别名', trigger: 'blur'},
						{pattern: /^[a-zA-Z0-9_-]+$/, message: '只允许英文或者拼音,横杠(-),下划线(_)', trigger: 'blur'}
					]
				},
				editFormVisible: false,
				editFormLoading: false,
				listLoading: false,
				myFormTitle: '编辑',
			}
		},
		methods: {
			getData: function () {
                this.listLoading = true;
                this.axios.get('/categorys').then( (response) => {
                    let res = response.data;
                    if (res != false) {
                        this.tableData = res;
                        this.listLoading = false;
                    } else {
                        this.$message({
                            message: '数据获取失败',
                            type: 'error',
                            duration: 3*1000
                        });
                    }
                }).catch(function (error) {
                    console.log(error);
                    this.listLoading = false;
                });
			},
			handleCreate: function () {
				this.myFormTitle = '新增';
                this.myForm.id = 0;
                this.editFormVisible = true;
                this.setTopCategorys();
			},
			handleEdit: function (row) {
			    this.setTopCategorys();
			    this.editFormLoading = true;
			    this.myFormTitle = '编辑';
			    this.editFormVisible = true;
			    this.axios.get('/categorys/' + row.id).then( (response) => {
			        let res = response.data;
			        if (!res) {
			            this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					} else {
			            this.myForm = res;
			            this.parentids = res.parents;
					}
			        this.myForm = res;
			        this.editFormLoading = false;
				}).catch(function (error) {
				    console.log(error);
                    this.editFormLoading = false;
				});
			},
			handleDistory: function (row) {
				this.$confirm('确认要删除该分类么?', '提示', {}).then( ()=> {
					this.axios.delete('/categorys/destory', {data: {id: row.id}}).then( (response) => {
						let res = response.data;
						this.$message({
							message: res.msg,
							type: res.status
						});
					}).catch(function (error) {
						console.log(error);
					});
				}).catch(function (error) {
					console.log(error);
				});
			},
			submitMyForm: function (myForm) {
				var $_this = this;
				$_this.$refs[myForm].validate((valid) => {
					if (!valid) {
						console.log('myForm valid error');
						return false;
					}

					if($_this.myForm.id > 0) {
						$_this.axios.put('/categorys/update', $_this.myForm).then(function (response) {
							let res = response.data;
							$_this.$message({
								message: res.status == 'success' ? '编辑成功' : '编辑失败',
								type: res.status
							});
							if (res.status == 'success') {
								$_this.closeForm('myForm');
								$_this.getData();
							}
						}).catch(function (error) {console.log(error);});
					} else {
						$_this.axios.post('/categorys', $_this.myForm).then(function (response) {
							let res = response.data;
							if (res.status == 'success') {
								$_this.closeForm('myForm');
								$_this.getData();
							}
							$_this.$message({
								message: res.status == 'success' ? '新增成功' : '新增失败',
								type: res.status
							});
						}).catch(function (error) {
							if (error.response) {
								if (error.response.status == 422) {
									for (var index in error.response.data) {
										$_this.$notify({
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
					}
				});
			},
			closeForm: function (myForm) {
				this.editFormVisible = false;
				this.$refs[myForm].resetFields();
				this.myForm = {
					id: 0,
					category_name: '',
					category_flag: '',
					category_description: '',
					category_parent: 0
				};
				console.log('closeForm');
			},
			setTopCategorys: function () {
				var categorys = this.tableData.concat();
				categorys.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
				/*categorys.forEach(opt=> {
				    opt.value = parseValueToInt(opt.value);
				    if(opt.children && opt.children.length > 0) {
				        parseValueToInt(opt.children);
					}
				});*/
				this.categorys = categorys;
			}
		},
		components: {
            treeTable,
		},
		mounted() {this.getData();}
	}

</script>