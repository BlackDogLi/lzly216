<template>
    <el-row>
        <el-col :span="24">
            <el-button type="primary" icon="el-icon-plus">新增</el-button>
        </el-col>

        <el-col :span="24">
            <el-table :data="tableData" v-loading="tableLoading">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="img_pname" label="广告位名称" ></el-table-column>
                <el-table-column prop="img_flag" label="广告位标识" ></el-table-column>
                <el-table-column prop="img_width" label="广告位宽度" ></el-table-column>
                <el-table-column prop="img_height" label="广告位高度" ></el-table-column>
                <el-table-column prop="img_status" label="状态" ></el-table-column>
                <el-table-column label="操作" >
                    <template slot-scope="scope">
                        <el-button size="small" icon="el-icon-edit" @click="handleEdit()"></el-button>
                        <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete()"></el-button>
                    </template>
                </el-table-column>
            </el-table>

        </el-col>
    </el-row>
</template>

<script type="text/ecmascript-6">
    export default {
        data() {
            return {
                tableData: [],
                tableLoading: false,
            }
        },
        methods: {
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
        },
        mounted() {
            this.getData();
        }

    }

</script>