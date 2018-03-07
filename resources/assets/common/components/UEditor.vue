<template>
	<div :id=id type="text/plain" ref="editor"></div>
</template>
<style></style>
<script>
	//引入相应配置文件
	import '../../../../public/plug/UE/ueditor.config';
	import '../../../../public/plug/UE/ueditor.all.min';
	//import '../../../../public/plug/UE/ueditor.parse.min';
	import '../../../../public/plug/UE/lang/zh-cn/zh-cn';
	export default {
		name: 'ueditor',
		props: {
			defaultMsg: {type: String},
			config: {type: Object}
		},
		data() {
			return {
				editor: null,
				id: Math.ceil(Math.random()*1000) + 'editor'
				//id: Math.random().toString(16).substring(2) + 'ueditorId'
			}
		},
		methods: {
			getUEContent() {
				return this.editor.getContent();
			},
			destroyed() {
				return this.editor.destory();
			}

		},
		mounted() {
			let $_this = this;
			this.$refs.editor.id = this.id;
			this.editor = UE.getEditor(this.id, this.config);
			this.editor.addListener("ready", function() {
				//$_this.editor.execCommand('drafts');
				//var Msg = $_this.editor.execCommand('getlocaldata');
				//if (Msg){
					$_this.editor.setContent($_this.defaultMsg);
				//}

			});
		}
	}

</script>