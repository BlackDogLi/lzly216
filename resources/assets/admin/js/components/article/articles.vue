<template>
    <el-row>
        <el-col :span="24">
            <router-link to="/articles/add">
                <el-button type="primary" icon="el-icon-plus">新增</el-button>
            </router-link>
            <el-button type="primary" @click="handleDistory('multi', {})" icon="el-icon-delete">删除</el-button>
            <el-select v-model="category_id" clearable @change="filterCategory" placeholder="请选择">
                <el-option v-for="item in categorys" :label="item.category_name" :value="item.id" :key="item.id">
                </el-option>
            </el-select>
            <el-input v-model="q" placeholder="请输入内容" icon="el-icon-search" style="width:200px" :on-icon-click="searchBtn"></el-input>
        </el-col>
        <el-col>
            <el-table :data="listData" v-loading="listLoading" style="width: 100%" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="created_at"  :formatter="formatterDate" sortable label="日期" width="200">
                </el-table-column>

                <el-table-column sortable label="标题" width="350">
                    <template slot-scope="scope">
                        <router-link :to="{path: '/articles/edit/'+ scope.row.id}" class="links">{{scope.row.title}}</router-link>
                    </template>
                </el-table-column>
                <el-table-column label="分类" width="250">
                    <template slot-scope="scope">
                        <span v-if="scope.row.categories">{{scope.row.categories.category_name}}</span>
                    </template>
                </el-table-column>
                <el-table-column sortable label="标签" width="300">
                    <template slot-scope="scope">
                        <el-tag v-for="tag in scope.row.tags" type="primary" :key="tag.id">{{tag.tags_name}}</el-tag>
                    </template>
                </el-table-column>

                <el-table-column  label="操作" width="250">
                    <template slot-scope="scope">
                        <router-link :to="{ path: '/articles/edit/'+ scope.row.id}">
                            <el-button size="small" icon="el-icon-edit"></el-button>
                        </router-link>
                        <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDistory('one', scope.row)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
        <el-col>
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage"
                    :page-size="pageSize"
                    layout="prev, pager, next"
                    :total="total" class="pull-right">
            </el-pagination>
            <!--<el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page="currentPage"
                    :page-sizes="[20, 50, 80, 100, 200]"
                    :page-size="pageSize"
                    layout="sizes, prev, pager, next"
                    :total="total" class="pull-right">
            </el-pagination>-->
        </el-col>
    </el-row>
</template>

<script type="text/ecmascript-6">
	export default {
		data() {
			return {
				listData: [],
				category_id: '',
				categorys: [],
				currentPage: 1,
				total: 0,
				pageSize: 20,
				listLoading: false,
				checkedAll: [],
				q: ''
			}
		},
		methods: {
			formatterDate: function (row, column) {
				if (row.updated_at == '') {
					return '';
				}
				return this.util.formatDate(row.updated_at);
			},
			filterCategory: function (value) {
				this.getData();
			},
			searchBtn: function (event) {
				this.getData();
			},
			getData: function () {
				let _self = this;
				_self.listLoading = true;
				let query = {
					rows: _self.pageSize,
                    page: _self.currentPage,
					category_id: _self.category_id,
					q: _self.q
				};
				_self.axios.get('/articles', {params: query}).then(function (response) {
					let res = response.data;
					if (res != false) {
						_self.listData = res.data;
						_self.total = res.total;
						_self.currentPage = res.current_page;
						_self.listLoading = false;
					} else {
						_self.$message({
							message: '数据获取失败',
							type: 'error',
							duration: 3 * 1000
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
				this.getData();
			},
			handleDistory: function (type, row) {
				var _self = this, idsParam = {};
				switch (type) {
					case 'one':
						if (parseInt(row.id) <= 0) {
							_self.$message({
								message: '请选择需要删除的数据',
								type: 'warning'
							});
							return false;
						}
						idsParam = {ids: [row.id]};
						break;
					case 'multi':
						var ids = _self.util.getIdByArr(_self.checkedAll);
						if (ids.length <= 0) {
							_self.$message({
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
				_self.$confirm('确认删除该记录吗?', '提示', {}).then( () => {
					_self.listLoading = true;
					_self.axios.delete('/articles/destroy', {data: idsParam}).then(function (response) {
						_self.listLoading = false;
						let res = response.data;
						_self.$message({
							message: res.status == 'success' ? '删除成功' : '删除失败',
							type: res.status
						});
						if (type == 'one') {
							_self.util.removeByValue(_self.listData, row.id);
						} else {
							for (var index in _self.checkedAll) {
								_self.util.removeByValue(_self.listData, _self.checkedAll[index].id);
							}
						}
					}).catch(function (error) {
						console.log(error);
					});
				}).catch(() => {
					_self.listLoading = false;
				});
			},
			handleSelectionChange (val) {
				this.checkedAll = val;
			},
			getCategorys: function () {
				let _self = this;
				_self.axios.get('/categorys', {params: {rows: this.pageSize, page:this.currentPage}}).then(function (response) {
					let res = response.data;
					if (res != false) {
						res.data.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});

						_self.categorys = res.list;
					} else {
						_self.$message({
							message: '数据获取失败',
							type: 'error',
							duration: 3 * 1000
						});
					}
				}).catch(function (error) {
					console.log(error);
				});
			},
			setTopCategorys: function () {
				var categorys = this.listData.concat();
				categorys.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
				this.categorys = categorys;
			}
		},
		mounted() {
			this.getCategorys();
			this.getData();
		}
	}
</script>