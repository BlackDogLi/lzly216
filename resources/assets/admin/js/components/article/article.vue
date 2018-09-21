<template>
	<el-container>
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
                        <el-input type="textarea" ref="myMarkdown" @change="textcomplete" :autosize="{ minRows: 12}" :rows="textareaRow" v-model="myForm.markdown"></el-input>
                        <!--<div class="editor-container">
                            <el-button @click="getUEContent2"></el-button>
                            <UE :defaultMsg=defaultMsg :config=config ref="myMarkdown" :v-model="myForm.markdown"></UE>
                        </div>-->
                        <!--<div id="ue"  v-model="myForm.markdown" class="editor-container">
                        </div>-->
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="closeForm('myForm')">取消</el-button>
                        <el-button @click="clearCache()">清缓存</el-button>
                        <el-button type="primary" @click="submitMyForm('myForm')">确 定</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col>
                <article class="markdown-previews markdown-body" v-html="markdownPreviews"></article>
            </el-col>
            <el-col>
                <el-dialog title="图片预览" v-model="previewVisible" size="large">
                    <div class="showPreview">
                        <div v-html="showPreview"></div>
                    </div>
                    <span slot="footer" class="dialog-footer"></span>
                    <el-button type="primary" @click="previewVisible = false">关 闭</el-button>
                </el-dialog>
            </el-col>
        </el-row>
    </el-container>
</template>
<style type="text/css">
</style>
<script type="text/ecmascript-6">
	import inlineAttachment from '../../lib/inline-attachment';
	import hotkeys from '../../lib/hotkeys.min';
	export default {
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
			}
		},
		methods: {
			clearCache: function () {
				let $_this = this;
				$_this.localforage.removeItem('myFormMarkdown').then(function () {
					console.log('Key is cleared!');
					$_this.myForm.markdown = '';
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
				var $_this = this;
				let query = $_this.myForm.title;
				if (query.match(/\w+/g) != null) {
					$_this.myForm.flag = query;
				}
				if (query == null || query == '') {
					return false;
				}
				$_this.axios.get('/util', {
					params: {action: 'translates', q: query}
				}).then(function (response) {
					let res = response.data;
					if (res.status == 200 && res.trans_result.dst) {
						let flag = res.trans_result.dst.toLowerCase();
						$_this.myForm.flag = flag.replaceAll('', '_', flag);
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
			/*emojies not do*/
			textcomplete: function (markdown) {
                /*
                 let emojies = [];
                 if (markdown.length <= 0) {
                 return false;
                 }
                 let re = /(^|\b)(\w{1,})$/;
                 let contentArr = re.exec(markdown);
                 if (contentArr != null) {
                 let str = contentArr[contentArr.length - 1];
                 emojies = this.util.searchEmojies(str);
                 this.emojiesData = emojies;
                 this.showEmojies = true;
                 this.emojiesStyle = {
                 zIndex: PopupManager.nextZIndex(),
                 };
                 } else {
                 this.emojiesData = [];
                 this.showEmojies = false;
                 }
                 console.log(emojies);
                 */
			},
			getPost: function (id) {
				if (parseInt(id) <= 0) {
					return false;
				}
				let $_this = this;
				$_this.editFormLoading = true;
				$_this.myFormTitle = '编辑';
				$_this.axios.get('/articles/' + id).then(function (response) {
					let res = response.data;
					if (res != false) {
						let tags = [];
						$_this.myForm = res;
						//tags
						if (res.tags.length > 0) {
							for (let index in res.tags) {
								tags.push(res.tags[index].tags_name);
							}
							$_this.myForm.tags = tags;
						}
						//thumb
						if (res.thumb != '') {
							$_this.fileList = [{url: res.thumb}];
						}
						$_this.compileMarkdown();
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error'
						});
					}
					$_this.editFormLoading = false;
				}).catch(function (error) {
					console.log(error);
					$_this.editFormLoading = false;
				});
			},
			submitMyForm: function (myForm) {
				let $_this = this;
				$_this.$refs[myForm].validate((valid) => {
					if (!valid) {
						console.log('myForm valid error.');
						return false;
					}
					if ($_this.myForm.id > 0) {
						$_this.axios.put('/articles/update', $_this.myForm).then(function (response) {
							let res = response.data;
							$_this.$message({
								message: res.status == 'success' ? '编辑成功' : '编辑失败',
								type: res.status
							});
							if (res.status == 'success') {
								$_this.closeForm('myForm');
							}
						}).catch(function (error) {
							console.log(error);
						});
					} else {
						$_this.axios.post('/articles', $_this.myForm).then(function (response) {
							let res = response.data;
							if (res.status == 'success') {
								$_this.closeForm('myForm');
							}
							$_this.$message({
								message: res.status == 'success' ? '新增成功' : '新增失败',
								type: res.status
							});
						}).catch(function (error) {
							if (error.response) {
								if (error.response.status == 422) {
									for (let index in error.response.data) {
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
				this.localforage.removeItem('myFormMarkdown').then(function () {
					console.log('Key is cleared!');
				}).catch(function (error) {
					console.log(error);
				});
				this.$refs[myForm].resetFields();
				this.$router.replace('/articles');
				console.log('closeForm');
			},
			getCategorys: function () {
				let $_this = this;
				$_this.axios.get('/categorys', {params: {rows: 999}}).then(function (response){
					let res = response.data;
					if (res != false) {
						res.data.splice(0, 0, {id: 0, category_name: '顶级分类', hidden: true, category_parent: 0});
						$_this.categorys = res.data;
					} else {
						$_this.$message({
							message: '数据获取失败',
							type: 'error',
							duration: 3 * 1000
						});
					}
				}).catch(function (error) {
					console.log(error);
				});
			},
			compileMarkdown: function () {
				this.markdownPreviews = this.marked(this.myForm.markdown);
				//this.defaultMsg = this.myForm.markdown;
			},
			setDomain: function () {
				let location = window.location;
				this.domain = location.protocol + '//' + location.host + '/';
			},
			setMarkdown: function () {
				let $_this = this;
				this.localforage.getItem('myFormMarkdown').then(function (value) {
					if (value != '' && value != null) {
						$_this.myForm.markdown = JSON.parse(value);
					}
				}).catch(function (error) {
					console.log(error);
				});
			},
			inlineAttachment: function (el) {
				let $_this = this;
				inlineAttachment.editors.input.attachToInput(el, {
					uploadUrl: $_this.uploadsApi,
					uploadFileName: 'file',
					allowedTypes: ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'],
					progressText: '![文件上传中...]()',
					errorText: '文件上传失败,请重试',
					extraParams: {},
					extraHeaders: {'X-CSRF-TOKEN': Laravel.csrfToken},
					onFileUploaded: function (response) {
						$_this.compileMarkdown();
					}
				});
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
		},
		computed: {
			compiledMarkdown: function () {
				return marked(this.input, { sanitize: true});
				let $_this = this;
				$_this.compileMarkdown();
				let markdown2json = JSON.stringify($_this.myForm.markdown);
				this.localforage.setItem('myFormMarkdown', markdown2json).then(function (value) {
					//console.log(value);
				}).catch(function (error) {
					console.log(error);
				});
			}
		},
		watch: {
			'myForm.markdown': {
				handler: function (curVar, oldVar) {
					let $_this = this;
					$_this.compileMarkdown();
					let markdown2json = JSON.stringify($_this.myForm.markdown);
					this.localforage.setItem('myFormMarkdown', markdown2json).then(function (value) {

					}).catch(function (error) {
						console.log(error);
					});
				}
			},
			'$route'(to, form) {
				if (this.$route.params.id == undefined) {
					this.$refs['myForm'].resetFields();
					this.fileList = [];
				}
			}
		},
		mounted() {
			/*let $_this = this;
			this.editor = UE.getEditor('ue');
			this.editor.addListener("ready", function() {
				$_this.editor.setContent($_this.defaultMsg);
			});
			this.editor.addListener('contentChange',function () {
				$_this.getUEContent();
			});*/
			this.getCategorys();
			this.setDomain();
			this.setMarkdown();
			this.inlineAttachment(this.$refs.myMarkdown.$el.firstElementChild);
			this.hotkeys();
		}
	}
</script>