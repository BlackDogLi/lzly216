<template>
	<el-container>
		<el-row>
			<el-col>
				<el-button type="primary" @click="handleCreate" icon="el-icon-plus">新增</el-button>
				<el-button type="primary" @click="handleDistory('multi',{})" icon="el-icon-delete" >删除</el-button>
			</el-col>
			<el-col>
				<el-table :data="listData" v-loading="listLoading"  @selection-change="handleSelectionChange">
					<el-table-column type="selection" width="50"></el-table-column>
					<el-table-column prop="set_title" width="250" sortable label="配置项说明"></el-table-column>
					<el-table-column prop="set_name" sortable width="250" sortable label="配置项名称" ></el-table-column>
					<el-table-column prop="set_group" sortable width="200" sortable label="配置分组"></el-table-column>
					<el-table-column prop="set_remark" sortable width="200" label="配置项备注"></el-table-column>
					<el-table-column prop="created_at" sortable :formatter = "formatterDate" label="日期" width="200"></el-table-column>
					<el-table-column label="操作" width="250">
						<template slot-scope="scope">
							<el-button size="small" icon="el-icon-edit" @click="handleEdit(scope.row)"></el-button>
							<el-button type="danger" size="small" icon="el-icon-delete" @click="handleDistory('one', row)"></el-button>
						</template>
					</el-table-column>
				</el-table>
			</el-col>
			<el-col>
				<el-pagination
						@size-change = "handleSizeChange"
						@current-change = "handleCurrentChange"
						:page-size = "pageSize"
						layout = "prev, pager, next"
						:total = "total" class="pull-right">
				</el-pagination>
			</el-col>
		</el-row>
		<el-row>
			<el-col>
				<el-dialog :title="myFormTitle"  v-model="editFormVisible" :visible.sync="editFormVisible">
					<el-form ref = "myForm" :rules = "myRules" class = "myForm" label-width = "100px" :model="myForm">
						<el-form-item label="配置项说明" prop="set_title">
							<el-input v-model="myForm.set_title" auto-complete="off"></el-input>
						</el-form-item>
						<el-form-item label="配置项名称" prop="set_name">
							<el-input v-model="myForm.set_name" auto-complete="off"></el-input>
						</el-form-item>
						<el-form-item label="配置项值">
							<el-input :type="myForm.data_type" :autosize="myForm.data_type == 'textarea'" v-model="myForm.set_value" auto-complete="off"></el-input>
						</el-form-item>
						<el-form-item label="配置分组">
							<el-input v-model="myForm.set_group" auto-complete="off"></el-input>
						</el-form-item>
						<el-form-item label="配置项备注">
							<el-input type="textarea" :autosize="{ minRows: 3, maxRows: 5}" v-model="myForm.set_remark" auto-complete="off"></el-input>
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
	</el-container>
</template>
<script type = "text/ecmascript-6">
	export default {
		data () {
			return {
				listData: [],
				currentPage: 1,
				total: 0,
				pageSize: 20,
				myForm: {
					id: 0,
					set_title: '',
					set_name: '',
					set_value: '',
					set_group: '',
					set_remark: ''
				},
				myRules: {
					set_title: [
						{required: true, type: "string", message: '请填写配置项说明', trigger: 'blur'}
					],
					set_name: [
						{required: true, type: "string", message: '请填写配置项名称', trigger: 'blur'},
						{pattern: /^[a-zA-Z0-9_]+$/, message: '只允许英文或者拼音,横杠(-)',trigger: 'blur'}
					]
				},
				editFormVisible: false,
				editFormLoading: false,
				listLoading: true,
				myFormTitle: '编辑',
				checkedAll: []
			}
		},
		methods: {
			formatterDate: function (row, column) {
				if (row.updated_at == '') {
					return '';
				}
				return this.util.formatDate(row.updated_at);
			},
			getData: function () {
				var $_this = this;
				$_this.listLoading = true;
				$_this.axios.get('/options', {
					params: {
						rows: this.pageSize
					}
				}).then (function (response) {
					let res = response.data;
					if (res != false) {
						$_this.listData = res.data;
						$_this.total = res.total;
						$_this.currentPage = res.current_page;
						$_this.listLoading = false;
					} else {
						$_this.$message ({
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
				this.pageSize = val;
				this.getData();
			},
			handleCurrentChange (val) {
				this.currentPage = val;
			},
			handleCreate: function () {
				var $_this = this;
				$_this.myFormTitle =  '新增';
				$_this.myForm.id = 0;
				$_this.editFormVisible = true;
			},
			handleEdit: function (row) {
				var $_this = this;
				$_this.editFormLoading = true;
				$_this.myFormTitle = '编辑';
				$_this.editFormVisible = true;
				$_this.axios.get('/options/' + row.id).then(function (response) {
					let res = response.data;
					if (res != false) {
						$_this.myForm = res;
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					$_this.editFormLoading = false;
				}).catch (function (error) {
					console.log(error);
					$_this.editFormLoading = false;
				});
			},
			handleDistory: function (type, row) {
				var $_this = this, idsParam = {};
				switch (type) {
					case 'noe':
						if (parseInt (row.id) <= 0) {
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
								message: '请选择需要删除的数据',
								type: 'warning'
							});
							return false;
						}
						idsParam = {ids: ids};
						break;
					default:
						break;
				}

				$_this.$confirm ('确认删除该记录吗?', '提示', {
				}).then(() => {
					$_this.listLoading = true;
					$_this.axios.delete('/options/destory', {data: idsParam}).then(function (response) {
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
						console.log('myForm valid error.');
						return false;
					}

					if ($_this.myForm.id > 0) {
						$_this.axios.put('/options/update', $_this.myForm).then(function (response) {
							let res = response.data;
							$_this.$message({
								message: res.status == 'success' ? '编辑成功' : '编辑失败',
								type: res.status
							});
							if (res.status == 'success') {
								$_this.closeForm('myForm');
								$_this.getData();
							}
						}).catch(function (error) {
							console.log(error);
						});
					} else {
						$_this.axios.post('/options', $_this.myForm).then(function (response) {
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
					name: '',
					url: '',
					logo: '',
					group: '',
				};
				console.log('closeForm');
			},
			handleSelectionChange (val) {
				this.checkedAll = val;
			}
		},
		watch: {},
		mounted () {
			this.getData();
		}
	}
</script>