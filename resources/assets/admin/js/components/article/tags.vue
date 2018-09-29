<template>
    <el-container>
        <el-row>
            <el-col>
                <el-button type="primary" @click="handleCreate" icon="el-icon-plus">新增</el-button>
                <el-button type="primary" @click="handleDistory('multi',{})" icon="el-icon-delete">删除</el-button>
            </el-col>
            <el-col>
                <el-table :data="listData" v-loading="listLoading"  @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55"></el-table-column>
                    <el-table-column prop="tags_name" sortable label="标签名称" width="350"></el-table-column>
                    <el-table-column prop="tags_flag" sortable :formatter="formatterFlag" label="标签别名" width="350"></el-table-column>
                    <el-table-column prop="created_at" sortable :formatter="formatterDate" label="日期" width="350"></el-table-column>
                    <el-table-column label="操作" width="350">
                        <template slot-scope="scope" >
                            <el-button size="small" icon="el-icon-edit" @click="handleEdit(scope.row)"></el-button>
                            <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDistory('one',scope.row)"></el-button>
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
            </el-col>
        </el-row>
        <el-row>
            <el-col>
                <el-dialog :title="myFormTitle" v-model="editFormVisible" :visible.sync="editFormVisible">
                    <el-form ref="myForm" :rules="myRules" class="myForm" label-width="80px" :model="myForm">
                        <el-form-item label="标签名称" prop="tags_name">
                            <el-input v-model="myForm.tags_name" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="标签别名" prop="tags_flag">
                            <el-input v-model="myForm.tags_flag" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item v-if="myForm.id">
                            <el-input v-model="myForm.id" style="display: none;"></el-input>
                        </el-form-item>
                    </el-form>
                    <div slot="footer" class="dialog-footer">
                        <el-button @click="closeForm('myForm')">取 消</el-button>
                        <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
                    </div>
                </el-dialog>
            </el-col>
        </el-row>
    </el-container>
</template>
<script type="text/ecmascript-6">
    export default{
        data(){
            return {
                listData: [],
                categorys: [],
                currentPage: 1,
                total: 0,
                pageSize: 20,
                myForm: {
                    id: 0,
                    tags_name: '',
                    tags_flag: ''
                },
                myRules: {
                    tags_name: [
                        {required: true, type: "string", message: '请填写分类名称', trigger: 'blur'}
                    ],
                    tags_flag: [
                        {required: true, type: "string", message: '请填写分类别名', trigger: 'blur'}
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
            formatterDate: function (row, column) {
                if (row.updated_at == '') {
                    return '';
                }
                return this.util.formatDate(row.updated_at);
            },
            formatterFlag: function (row, column) {
                if (row.tags_flag == '') {
                    return '';
                }
                return decodeURI(row.tags_flag);
            },
            getData: function () {
                var _self = this;
                _self.listLoading = true;
                _self.axios.get('/tags', {
                    params: {
                        rows: this.pageSize,
                        page: this.currentPage
                    }
                }).then(function (response) {
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
            handleSizeChange(val) {
                this.pageSize = val;
                this.getData();
            },
            handleCurrentChange(val) {
                this.currentPage = val;
                this.getData();
            },
            handleCreate: function () {
                var _self = this;
                _self.myFormTitle = '新增';
                _self.myForm.id = 0;
                _self.editFormVisible = true;
                _self.setTopCategorys();
            },
            handleEdit: function (row) {
                var _self = this;
                _self.setTopCategorys();
                _self.editFormLoading = true;
                _self.myFormTitle = '编辑';
                _self.editFormVisible = true;
                _self.axios.get('/tags/' + row.id).then(function (response) {
                    let res = response.data;
                    if (res != false) {
                        res.tags_flag = decodeURI(res.tags_flag);
                        _self.myForm = res;
                    } else {
                        _self.$message({
                            message: '数据获取失败',
                            type: 'error'
                        });
                    }
                    _self.editFormLoading = false;
                }).catch(function (error) {
                    console.log(error);
                    _self.editFormLoading = false;
                });
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

                _self.$confirm('确认删除该记录吗?', '提示', {
                    //type: 'warning'
                }).then(() => {
                    _self.listLoading = true;
                    _self.axios.delete('/tags/destroy', {data: idsParam}).then(function (response) {
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
            submitMyForm: function (myForm) {
                var _self = this;
                _self.$refs[myForm].validate((valid) => {
                    if (!valid) {
                        console.log('myForm valid error.');
                        return false;
                    }

                    if (_self.myForm.id > 0) {
                        _self.axios.put('/tags/update', _self.myForm).then(function (response) {
                            let res = response.data;
                            _self.$message({
                                message: res.status == 'success' ? '编辑成功' : '编辑失败',
                                type: res.status
                            });
                            if (res.status == 'success') {
                                _self.closeForm('myForm');
                                _self.getData();
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else {
                        _self.axios.post('/tags', _self.myForm).then(function (response) {
                            console.log(response);

                            let res = response.data;
                            if (res.status == 'success') {
                                _self.closeForm('myForm');
                                _self.getData();
                            }
                            _self.$message({
                                message: res.status == 'success' ? '新增成功' : '新增失败',
                                type: res.status
                            });
                        }).catch(function (error) {
                            if (error.response) {
                                if (error.response.status == 422) {
                                    for (var index in error.response.data) {
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
                    }
                });
            },
            closeForm: function (myForm) {
                this.editFormVisible = false;
                this.$refs[myForm].resetFields();
                this.myForm = {
                    id: 0,
                    tags_name: '',
                    tags_flag: ''
                };
                console.log('closeForm');
            },
            handleSelectionChange(val) {
                this.checkedAll = val;
            },
            setTopCategorys: function () {
                var categorys = this.listData.concat();
                categorys.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
                this.categorys = categorys;
            }
        },
        watch: {
            'myForm.tags_name': {//监听路由改变
                handler(curVal, oldVal){
                    this.myForm.tags_flag = curVal.replace(' ', '-');
                },
                deep: true
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
