<template>
    <el-row>
        <el-col :span="24">
            <el-button type="primary" icon="el-icon-plus" @click="handleCreate">新增</el-button>
            <!--<el-button type="primary" @click="handleDistory('multi', {})" icon="el-icon-delete">删除</el-button>-->
        </el-col>

        <el-col :span="24">
            <el-table :data="tableData" v-loading="tableLoading" @selection-change="handleChecked">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="img_pname" label="广告位名称" ></el-table-column>
                <el-table-column prop="img_flag" label="广告位标识" ></el-table-column>
                <el-table-column prop="img_width" label="广告位宽度" ></el-table-column>
                <el-table-column prop="img_height" label="广告位高度" ></el-table-column>
                <el-table-column prop="img_status" label="状态" ></el-table-column>
                <el-table-column label="操作" >
                    <template slot-scope="scope">
                        <el-button size="small" icon="el-icon-edit" @click="handleEdit(scope.row)"></el-button>
                        <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete('one', scope.row)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>

        <el-col :span="24">
            <el-dialog :title="myFormTitle" :visible.sync="editFormVisible">
                <el-form ref="myForm" :rules="formRules" status-icon label-width="80px;" :model="myForm">
                    <el-form-item label="广告位名称" prop="img_pname">
                        <el-input v-model="myForm.img_pname" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="广告位标识" prop="img_flag">
                        <el-input v-model="myForm.img_flag" auto-complete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="广告位宽度" prop="img_width">
                        <el-input v-model.number="myForm.img_width"></el-input>
                    </el-form-item>
                    <el-form-item label="广告位高度" prop="img_height">
                        <el-input v-model.number="myForm.img_height"></el-input>
                    </el-form-item>
                    <el-form-item label="图片大小" prop="img_size">
                        <el-input v-model.number="myForm.img_size"></el-input>
                    </el-form-item>
                    <el-form-item label="是否启用" >
                        <el-switch v-model="myForm.img_status"></el-switch>
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
    export default {
        data() {
            return {
                tableData: [],
                tableLoading: false,
                myFormTitle: '编辑',
                editFormVisible: false,
                checkedAll:[],
                myForm: {
                    id: 0,
                    img_pname: '',
                    img_flag: '',
                    img_width: 0,
                    img_height: 0,
                    img_size: 0,
                    img_status: true,
                },
                formRules: {
                    img_pname: [
                        {required: true, type: 'string', message: '请填广告位名称', trigger: 'blur'}
                    ],
                    img_flag: [
                        {required: true, type: 'string', message: '请填广告位标识', trigger: 'blur'},
                        {pattern: /^[a-zA-Z0-9_-]+$/, message: '只允许英文或者拼音,横杠(-),下划线(_)', trigger: 'blur'}
                    ],
                    img_width: [
                        {required: true, type: 'number', message:'广告位宽度必须为数值', trigger: 'blur'}
                    ],
                    img_height: [
                        {required: true, type: 'number', message:'广告位高度必须为数值', trigger: 'blur'}
                    ],
                    img_size: [
                        {required: true, type: 'number', message:'广告位图片大小必须为数值', trigger: 'blur'}
                    ],
                },
            }
        },
        methods: {
            //列表获取数据
            getData: function () {
                var _self = this;
                _self.tableLoading = true;
                _self.axios.get('/imgposition').then(function (response) {
                    let res = response.data;
                    if (res) {
                        _self.tableData = res;
                        _self.tableLoading = false;
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
            //新增弹框
            handleCreate: function () {
                this.myFormTitle = '新增';
                this.editFormVisible = true;
            },
            //编辑弹框
            handleEdit: function (row) {
                var _self = this;
                _self.editFormVisible = true;
                _self.axios.get('/imgposition/' + row.id).then(function (response) {
                    let res = response.data;
                    if (res.status == 200) {
                        _self.myForm = res.data;
                        _self.myForm.img_status = res.data.img_status == 1 ? true : false;
                    } else {
                        _self.$message({
                            message: '数据获取失败',
                            type: 'error',
                        });
                    }
                }).catch(function (error) {
                   console.log(error);
                });

            },
            //提交数据
            submitMyForm: function (formName) {
                var _self = this;
                _self.$refs[formName].validate((validate) => {
                    if (!validate) {
                        console.log('myForm validate error');
                        return false;
                    }
                    if (_self.myForm.id > 0) {
                        //编辑
                        _self.axios.put('/imgposition/update', _self.myForm).then(function (response) {
                            let res = response.data;
                            _self.$message({
                                message: res.msg,
                                type: res.status == 200 ? 'success' : 'error',
                            });
                            if (res.status == 200) {
                                _self.closeForm('myForm');
                                _self.getData();
                            }
                        }).catch( function (error) {
                            console.log(error);
                        });
                    } else {
                        //新增
                        _self.axios.post('/imgposition', _self.myForm).then(function (response) {
                            let res = response.data;
                            _self.$message({
                                message: res.msg,
                                type: res.status == 200 ? 'success' : 'error',
                            });
                            if (res.status == 200) {
                                _self.closeForm('myForm');
                                _self.getData();
                            }
                        }).catch( function (error) {
                            console.log(error);
                        });
                    }
                });
            },
            //删除
            handleDelete: function (type, row) {
                var _self = this;
                var idsParam = {};
                console.log(type);
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
                    _self.axios.delete('/imgposition/destroy', {data: idsParam}).then(function (response) {
                        _self.tableLoading = false;
                        let res = response.data;
                        _self.$message({
                            message: res.msg,
                            type: res.status == 200 ? 'success' : 'error',
                        });
                        if (type == 'one') {
                            _self.util.removeByValue(_self.tableData, row.id);
                        } else {
                            for (var index in _self.checkedAll) {
                                _self.util.removeByValue(_self.tableData, _self.checkedAll[index].id);
                            }
                        }

                    }).catch(function (error) {
                        console.log(error);
                    });
                }).catch(() => {
                    _self.tableLoading = false;
                });
            },
            //勾选事件
            handleChecked: function (val) {
                this.checkedAll = val;
            },
            //取消
            closeForm: function (formName) {
                this.$refs[formName].resetFields();
                this.editFormVisible = false;
            }

        },
        mounted() {
            this.getData();
        }

    }

</script>