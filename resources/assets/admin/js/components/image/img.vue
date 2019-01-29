<template>
    <el-row>
        <el-col :span="24">
            <el-button type="primary" icon="el-icon-plus" @click="handleCreate">上传图片</el-button>
            <el-select v-model="position_id" clearable @change="filterPosition" placeholder="请选择" value-key="id">
                <el-option v-for="item in imgPositions" :label="item.img_pname" :value="item.id" :key="item.id">
                </el-option>
            </el-select>
        </el-col>

        <el-col :span="24">
            <el-table :data="tableData" v-loading="tableLoading" @selection-change="">
                <el-table-column type="selection" width="55"></el-table-column>
                <el-table-column label="图片" >
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

        <el-col :span="24">
            <el-dialog title="上传图片" :visible.sync="formVisible">
                <el-form ref="myForm" status-icon label-width="80px;">
                    <el-form-item label="图片位置">
                        <el-select v-model="form.position" placeholder="请选择图片位置" value-key="id">
                            <el-option v-for="item in imgPositions" :label="item.img_pname" :value="item" :key="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="图片上传">
                        <el-upload
                            :file-list="fileList"
                            list-type="picture-card"
                            ref = "upload"
                            :action="uploadsApi"
                            :headers="headers"
                            :data="form.position"
                            multiple
                            :auto-upload="false"
                            :before-upload="handlePictureUpload"
                            :on-prview="handlePicturePreview"
                            :on-remove="handlePictureRemove"
                            :on-change="handlePictureChange">
                                <img v-if="imageUrl" :src="imageUrl" class="post-cover">
                                <i v-else class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="closeForm">取消</el-button>
                        <el-button type="primary" @click="submitMyForm">确定</el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
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
                position_id: '',
                currentPage: 1,
                pageSize: 20,
                total: 0,
                formVisible: false,
                uploadsApi: Laravel.apiUrl + '/uploads',
                imageUrl: '',
                fileList: [],
                uploadData: {

                },
                form : {},
                myForm: new FormData,

                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
            };
        },
        methods: {
            //获取初始数据
            getData: function () {
                var _self = this;
                _self.tableLoading = true;
                let query = {
                    rows: _self.pageSize,
                    page: _self.currentPage,
                    position_id: _self.position_id
                };
                _self.axios.get('/img',{params:query}).then(function (response) {
                    let res = response.data;
                    if (res) {
                        _self.imgPositions = res.imgposition;
                        _self.tableData = res.img.data;
                        _self.total = res.img.total;
                        _self.currentPage = res.img.current_Page;
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
            filterPosition: function (val) {
                this.getData();
            },
            //设置分页大小
            handleSizeChange: function (val) {
                this.pageSize = val;
                this.getData();
            },
            //设置当前页码
            handleCurrentChange: function (val) {
                this.currentPage = val;
                this.getData();
            },
            //图片预览
            handlePicturePreview: function (file) {
                this.imageUrl = file.url;
            },
            //图片删除
            handlePictureRemove: function (file, filelist) {
                this.myForm.fileList = filelist;
            },

            //添加删除图片列表
            handlePictureChange: function (file) {
                this.myForm.append('fileList[]', file.raw);
            },
            //图片上传之前的校验
            handlePictureUpload: function (file) {
                let _self = this;
                const imageCheck = new Promise(function (resolve, reject) {
                    let width = _self.myForm.position.img_width;
                    let height = _self.myForm.position.img_height;
                    let _URL = window.URL || window.webkitURL;
                    let img = new Image();
                    img.onload = function (){
                        let valid = img.width <= width && img.height <= height;
                        valid ? resolve() : reject();
                    };
                    img.src = _URL.createObjectURL(file);
                }).then(() => {
                    return file;
                }, () => {
                    this.$message({
                        type: 'warning',
                        message: '图片必须小于或等于' + this.myForm.position.img_width + '*' +this.myForm.position.img_height,
                    });
                    return Promise.reject();
                });
                return imageCheck;
            },
            handleDelete: function (row) {

            },

            //状态格式化
            formStatus: function (row) {
                return row.status == 1 ? '启用' : '禁用';
            },
            //上传图片对话框
            handleCreate: function () {
                this.formVisible = true;
            },
            //取消关闭对话框
            closeForm: function () {
                this.formVisible = false;
                this.$refs.upload.clearFiles();
            },
            //上传图片
            submitMyForm: function () {
                let _self = this;
                _self.myForm.append('pos', _self.form.position.id);
                let config = {headers: {'Content-Type': 'application/x-www-form-urlencoded'}};
                _self.axios.post('/uploads',  _self.myForm, config).then(function (response) {
                    let res = response.data;
                    _self.$message({
                        message: res.msg,
                        type: res.status == 200 ? 'success' : 'error',
                    });
                    if (res.status == 200) {
                        _self.$refs.upload.clearFiles();  //去除组建内图片
                        _self.formVisible = false;        //关闭弹窗
                        _self.getData();
                    }
                }).catch(function(error) {
                    console.log(error);
                });

            },
        },
        watch:{
            'myForm.position': function (){
                this.fileList.position_id = this.myForm.position.id;
                this.$refs.upload.clearFiles();
            },
        },
        mounted() {
            this.getData();
        },
    }
</script>