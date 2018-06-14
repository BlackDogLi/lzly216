<template>
	<div class="main-content">
		<div class="main-action-btn">
			<el-button type="primary" @click="handleCreate" icon="el-icon-plus">新增</el-button>
			<el-button type="primary" @click="handleDistory('multi', {})" icon="el-icon-delete">删除</el-button>
		</div>

		<template>
			<el-table :data="listData" v-loading="listLoading" header-cell-class-name="tableheader" style="text-align: center; width:100%" @selection-change="handleSelectionChange">
				<el-table-column type="selection" width="55"></el-table-column>
				<el-table-column prop="category_name" sortable label="分类名称" width="300"></el-table-column>
				<el-table-column prop="category_flag" sortable label="分类别名" width="300"></el-table-column>
				<el-table-column label="操作">
					<template scope="scope">
						<el-button size="small" icon="el-icon-edit" @click="handleEdit(scope.row)"></el-button>
						<el-button type="danger" size="small" icon="el-icon-delete" @click="handleDistory('one', scope.row)"></el-button>
					</template>
				</el-table-column>
			</el-table>
		</template>

		<el-pagination
			@size-change="handleSizeChange"
			@current-change="handleCurrentChange"
			:current-page="currentPage"
			:page-sizes="[20, 50, 80, 100, 200]"
			:page-size="pageSize"
			layout="sizes, prev, pager, next"
			:total="total">
		</el-pagination>

		<el-dialog :title="myFormTitle" v-model="editFormVisible" :visible.sync="editFormVisible">
			<div v-loading="editFormLoading">
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
						<el-select v-model="myForm.category_parent" placeholder="请选择父分类">
							<el-option v-for="item in categorys" :label="item.category_name" :value="item.id"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item v-if="myForm.id">
						<el-input v-model="myForm.id" style="display: none;"></el-input>
					</el-form-item>
				</el-form>
				<div slot="footer" class="dialog-footer">
					<el-button @click="closeForm('myForm')">取消</el-button>
					<el-button type="primary" @click="submitMyForm('myForm')">确定</el-button>
				</div>
			</div>

		</el-dialog>
	</div>
</template>
<script type="text/ecmascript-6">
	export default {
		data() {
			return {
				listData: [],
				categorys: [],
				currentPage: 1,
				total: 0,
				pageSize: 20,
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
				checkedAll: []
			}
		},
		methods: {
			getData: function () {
				var $_this = this;
				$_this.listLoading = true;
				$_this.axios.get('/categorys', {
					params: {row: $_this.pageSize}
				}).then(function (response) {
					let res = response.data;
					if (res != false) {
						$_this.listData = res.data;
						$_this.total = res.total;
						$_this.currentPage = res.curren_page;
						$_this.listLoading = false;
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error',
							duration: 3*1000
						});
					}
				}).catch(function (error) {
					console.log(error);
				});
			},
			handleSizeChange (val) {
				$this.pageSize = val;
				$this.getData();
			},
			handleCurrentChange (val) {
				$this.currentPage = val;
			},
			handleCreate: function () {
				var $_this = this;
				$_this.myFormTitle = '新增';
				$_this.myForm.id = 0;
				$_this.editFormVisible = true;
				$_this.setTopCategorys();
			},
			handleEdit: function (row) {
				var $_this = this;
				$_this.setTopCategorys();
				$_this.editFormLoading = true;
				$_this.myFormTitle = '编辑';
				$_this.editFormVisible = true;
				$_this.axios.get('/categorys/' + row.id).then(function (response) {
					let res = response.data;
					if (res != false) {
						$_this.myForm = res;
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error',
						});
					}
					$_this.editFormLoading = false;
				}).catch(function (error) {
					console.log(error);
					$_this.editFormLoading = false;
				});
			},
			handleDistory: function (type, row) {
				var $_this = this, idsParam = {};
				switch (type) {
					case 'one':
						if (parseInt(row.id) <= 0) {
							$_this.$message({
								message: '请选择要删除的数据',
								type: 'warning'
							});
							return false;
						}
						idsParam = {ids: [row.id]};
						break;
					case 'multi':
						var ids = $_this.util.getIdByArr($_this.checkedAll);
						if (ids.length <= 0) {
							$_this.$message({
								message: '请选择要删除的数据',
								type: 'warning'
							});
							return false;
						}
						idsParam = {ids: ids};
						break;
					default:
						break;
				}
				$_this.$confirm('确认删除该记录么?', '提示', {}).then(() => {
					$_this.listLoading = true;
					$_this.axios.get('/categorys/destroy', {data: idsParam}).then(function (response) {
						$_this.listLoading = false;
						let res = response.data;
						$_this.$message({
							message: res.status == 'success' ? '删除成功' : '删除失败',
							type: res.status
						});
						if (type == 'one') {
							$_this.util.removeByValue($_this.listData, row.id);
						} else {
							for (var index in $_this.checkedAll) {
								$_this.util.removeByValue($_this.listData, $_this.checkedAll[index].id);
							}
						}
					}).catch(function (error) {
						console.log(error);
					});
				}).catch(() => {
					$_this.listLoading = false;
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
			handleSelectionChange (val) {
				this.checkedAll = val;
			},
			setTopCategorys: function () {
				var categorys = this.listData.concat();
				categorys.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
				this.categorys = categorys;
			}
		},
		mounted() {this.getData();}
	}
</script>