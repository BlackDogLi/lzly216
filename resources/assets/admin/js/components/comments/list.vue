<template>
    <el-row>
        <el-col :span="24">
            <el-button type="primary" icon="el-icon-plus">新增</el-button>
        </el-col>
        <el-col :span="24">
            <el-table :data="commentData" v-loading="listLoading">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="username" label="用户名" ></el-table-column>
                <el-table-column prop="nickname" label="昵称" ></el-table-column>
                <el-table-column prop="email" label="邮箱" ></el-table-column>
                <el-table-column prop="content" label="内容" ></el-table-column>
                <el-table-column prop="created_at" label="留言时间" ></el-table-column>
                <el-table-column label="操作" >
                    <template slot-scope="scope">
                        <el-button size="small" :icon="scope.row.status == 1 ? 'el-icon-success' : 'el-icon-error'" @click="handleEdit(scope.row)"></el-button>
                        <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete(scope.row)"></el-button>
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
</template>

<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                commentData: [],
                currentPage: 1,
                total: 0,
                pageSize: 20,
                listLoading: false,
            }
        },
        methods: {
            getData: function () {
                var _self = this;
                _self.listLoading = true;
                let params = {rows: _self.pageSize, page: _self.currentPage};
                _self.axios.get('/comments', {params: params}).then(function (response) {
                    let res = response.data;
                    if (res) {
                        _self.commentData = res.data;
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
            handleSizeChange: function (val) {
                this.pageSize = val;
                this.getData();
            },
            handleCurrentChange: function (val) {
                this.currentPage = val;
                this.getData();
            },
            handleEdit: function (val)
            {
                let _self = this;
                let status = val.status == 1 ? 0 : 1;
                _self.axios.put('/comments/update', {status: status, id: val.id}).then(function (response) {
                    let res = response.data;
                    if (res.status == 200) {
                            _self.getData();
                            _self.$message({
                                message: res.msg,
                                type: 'success',
                            });
                    } else {
                        _self.$message({
                            message: '修改失败',
                            type: 'error',
                            duration: 3 * 1000
                        });
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            handleDelete: function (val) {
                let _self = this;
                let param = {id: val.id};
                _self.axios.delete('/comments/destory', {data: param}).then(function (response) {
                    let res = response.data;
                    _self.$message({
                        message: res.msg,
                        type: res.status == 200 ? 'success' : 'error',
                    });
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getData();
        },
    }
</script>