<template>
    <el-row>
        <el-col :span="24">
            <el-button type="primary" icon="el-icon-plus" @click="">新增</el-button>
            <el-select v-model="category_id" clearable @change="" placeholder="请选择">
                <el-option v-for="item in imgPositions" :label="item.img_pname" :value="item.id" :key="item.id">
                </el-option>
            </el-select>
        </el-col>

        <el-col :span="24">
            <el-table :data="tableData" v-loading="tableLoading" @selection-change="">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column prop="img_path" label="图片" >
                    <template slot-scope="scope">
                        <img :src="scope.row.img_path" width="50" height="50"/>
                    </template>
                </el-table-column>
                <el-table-column label="位置名称" >
                    <template slot-scope="scope">
                        <span v-if="scope.row.positions">{{scope.row.positions.img_pname}}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="状态" :formatter="formStatus" ></el-table-column>
                <el-table-column prop="created_at" label="添加时间" ></el-table-column>
                <el-table-column label="操作" >
                    <template slot-scope="scope">
                        <el-button size="small" icon="el-icon-edit" @click="handleEdit(scope.row)"></el-button>
                        <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete('one', scope.row)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
    </el-row>
</template>

<script type="text/ecmascript-6">
    export default {
        data () {
            return {
                tableData: [],
                tableLoading: false,
                imgPositions: [],
            };
        },
        methods: {
            //获取初始数据
            getData: function () {
                var _self = this;
                _self.tableLoading = true;
                _self.axios.get('/img').then(function (response) {
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
            //状态格式化
            formStatus: function (row) {
                return row.status == 1 ? '启用' : '禁用';
            },
        },
        mounted() {
            this.getData();
        },
    }
</script>