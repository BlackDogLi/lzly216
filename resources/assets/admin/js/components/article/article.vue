<template>
    <el-row style="width:100%;">
        <el-col :span=24 class="shadow">
            <el-form ref="myForm" :model="myForm" :rules="myRules" label-width="80px" v-loading="editFormLoading">
                <el-form-item label="标题" prop="title">
                    <el-input v-model="myForm.title" @blur="titleBlur"></el-input>
                </el-form-item>
                <el-form-item label="别名" prop="flag">
                    <el-input placeholder="请输入内容" v-model="myForm.flag">
                        <template slot="prepend">{{domain}}</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="标签">
                    <el-tag v-for="tag in myForm.tags" :key="tag.id" type="primary" :closeable="true" :close-transition="false" @close="closeTags(tag)">{{tag}}</el-tag>
                    <el-input v-if="inputVisible" v-model="inputValue" ref="saveTagInput" size="mini" @keyup.enter.native="hanleInputConfirm" @blur="handleInputConfirm"></el-input>
                    <el-button v-else class="button-new-tag" size="small" @click="showTagsInput">+标签</el-button>
                </el-form-item>
                <el-form-item label="分类" prop="category_id">
                    <el-select v-model="myForm.category_id" placeholder="请选择分类">
                        <el-option v-for="item in categorys" :key="item.id" :label="item.category_name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="封面图">
                    <el-upload class="avatar-uploader" :action="uploadsApi" :headers="headers" :show-file-list="false" :on-success="coverUploadSuccess">
                        <img v-if="imageUrl" :src="imageUrl" class="post-cover">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="内容" prop="markdown">

                    <div class="editor-container">
                        <markdown @mdValue="getMdValue" :edit-content="editContent"></markdown>
                    </div>

                </el-form-item>
                <el-form-item>
                    <el-button @click="closeForm('myForm')">取消</el-button>
                    <el-button @click="clearCache()">清缓存</el-button>
                    <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <el-col>
            <el-dialog title="图片预览" v-model="previewVisible">
                <div class="showPreview">
                    <div v-html="showPreview"></div>
                </div>
                <span slot="footer" class="dialog-footer"></span>
                <el-button type="primary" @click="previewVisible = false">关 闭</el-button>
            </el-dialog>
        </el-col>
    </el-row>
</template>
<style type="text/css">
</style>
<script type="text/ecmascript-6">
	import inlineAttachment from '../../lib/inline-attachment';
	import hotkeys from '../../lib/hotkeys.min';

	import markdown from '../../../../common/components/markdown/mardown';

	export default {
	    components: {
	        markdown
        },
		//components: {UE},
		data(){
			return {
				defaultMsg: '',
				config: { initialFrameWidth: null, initialFrameHeight: 300},
				editFormLoading: false,
				loading: false,
				myFormTitle: '编辑',
				domain: 'http://',
				textareaRow: 15,
				categorys: [],
				fileList: [],
				markdownPreviews: '',
				inputVisible: false,
				inputValue: '',
				uploadsApi: Laravel.apiUrl + '/uploads',
				previewVisible: false,
				showPreview: '',
				imageUrl: '',
				myForm: {
					id: 0,
					title: '',
					flag: '',
					thumb: '',
					tags: [],
					category_id: 0,
					markdown: ''
				},
                editContent: '',
				headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
				myRules: {
					title: [{required: true, type: "string", message: '请填写文章标题', trigger: 'blur'}],
					flag: [
						{required: true, type: "string", message: '请填写文章别名', trigger: 'blur'},
						{pattern: /^[a-zA-Z0-9_-]+$/, message: '只允许英文或者拼音,横杠(-),下划线(_)', trigger: 'blur'}
					],
					category_id: [{required: true, type: "integer", message: '请选择分类', trigger: 'blur'}],
					markdown: [{required: true, type: "string", message: '文章内容不能为空', trigger: 'blur'}]
				},
			}
		},
		created () {
			if (this.$route.params.id != undefined) {
				this.getPost(this.$route.params.id);
			} else {
			    this.setMarkdown();
            }
		},
		methods: {
			clearCache: function () {
				let _self = this;
				_self.localforage.removeItem('mdTmp').then(function () {
					console.log('Key is cleared!');
					_self.editContent = '';
				}).catch(function (error) {
					console.log(error);
				});
			},
			closeTags: function (tag) {
				this.myForm.tags.splice(this.myForm.tags.indexOf(tag), 1);
			},
			showTagsInput() {
				this.inputVisible = true;
			},
			handleInputConfirm() {
				let inputValue = this.inputValue;
				if (inputValue) {
					this.myForm.tags.push(inputValue);
				}
				this.inputVisible = false;
				this.inputValue = '';
			},
			titleBlur: function (event) {
				var _self = this;
				let query = _self.myForm.title;
				if (query.match(/\w+/g) != null) {
					_self.myForm.flag = query;
				}
				if (query == null || query == '') {
					return false;
				}
				_self.axios.get('/util', {
					params: {action: 'translates', q: query}
				}).then(function (response) {
					let res = response.data;
					if (res.status == 200 && res.trans_result.dst) {
						let flag = res.trans_result.dst.toLowerCase();
						_self.myForm.flag = flag.replaceAll('', '_', flag);
					}
				}).catch(function (error) {
					console.log(error);
				});
			},
			coverUploadSuccess: function (response, file, fileList) {
				if (response.status == 200) {
					this.myForm.thumb = response.filename;
				} else {
					this.myForm.thumb = '';
				}
				this.imageUrl = response.url;
			},

            //获取mdEditor的值
            getMdValue: function (value) {
			    this.myForm.markdown = value.mdValue;
                //缓存编辑的数据
                let tmpMd = JSON.stringify(value.mdValue);
                this.localforage.setItem('mdTmp', tmpMd);
            },

			getPost: function (id) {
				if (parseInt(id) <= 0) {
					return false;
				}
				let _self = this;
				_self.editFormLoading = true;
				_self.myFormTitle = '编辑';
				_self.axios.get('/articles/' + id).then(function (response) {
					let res = response.data;
					if (res != false) {
						let tags = [];
						 _self.myForm = res;
						 _self.editContent = res.markdown;
						//tags
						if (res.tags.length > 0) {
							for (let index in res.tags) {
								tags.push(res.tags[index].tags_name);
							}
							_self.myForm.tags = tags;
						}
						//thumb
						if (res.thumb != '') {
							_self.fileList = [{url: res.thumb}];
						}
						//_self.compileMarkdown();
					} else {
						_self.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					_self.editFormLoading = false;
				}).catch(function (error) {
					_self.editFormLoading = false;
				});
			},
			submitMyForm: function (myForm) {
				let _self = this;
				_self.$refs[myForm].validate((valid) => {
					if (!valid) {
						console.log('myForm valid error.');
						return false;
					}
                    if (_self.myForm.id > 0) {
						_self.axios.put('/articles/update', _self.myForm).then(function (response) {
							let res = response.data;
							_self.$message({
								message: res.status == 'success' ? '编辑成功' : '编辑失败',
								type: res.status
							});
							if (res.status == 'success') {
								_self.closeForm('myForm');
							}
						}).catch(function (error) {
							console.log(error);
						});
					} else {
						_self.axios.post('/articles', _self.myForm).then(function (response) {
							let res = response.data;
							if (res.status == 'success') {
								_self.closeForm('myForm');
							}
							_self.$message({
								message: res.status == 'success' ? '新增成功' : '新增失败',
								type: res.status
							});
						}).catch(function (error) {
							if (error.response) {
								if (error.response.status == 422) {
									for (let index in error.response.data) {
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
				this.localforage.removeItem('mdTmp');
				this.$refs[myForm].resetFields();
				this.$router.replace('/articles');
			},
			getCategorys: function () {
				let _self = this;
				_self.axios.get('/categorys', {params: {rows: 999}}).then(function (response){
					let res = response.data;
					if (res != false) {
						res.list.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
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

			setDomain: function () {
				let location = window.location;
				this.domain = location.protocol + '//' + location.host + '/';
			},

			hotkeys: function () {
				hotkeys('ctrl+a,ctrl+b,r,f', function (event, handle) {
					switch (handle.key) {
						case "ctrl+a":
							console.log('you press ctrl+a!');
						case "ctrl+b":
							console.log('you press ctrl+b!');
						case "r":
							console.log('you press r!');
						case "f":
							console.log('you press f!');
							break;
					}
				});
			},
            setMarkdown: function () {
                let _self = this;
                this.localforage.getItem('mdTmp').then(function (value) {
                    if (value != '' && value != null) {
                        _self.myForm.markdown = JSON.parse(value);
                        _self.editContent = _self.myForm.markdown;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
		},
		computed: {
		},
		watch: {
			'$route'(to, form) {
				if (this.$route.params.id == undefined) {
					this.$refs['myForm'].resetFields();
					this.fileList = [];
				}
			}
		},
		mounted() {
			this.getCategorys();
			this.setDomain();
			this.hotkeys();
		}
	}
</script>