<template>
	<div :id="id"></div>
</template>
<style></style>
<script>
	//引入相应配置文件
	import '../../../../public/plug/UE/ueditor.config';
	import '../../../../public/plug/UE/ueditor.all.min';
	//import '../../../../public/plug/UE/ueditor.parse.min';
	import '../../../../public/plug/UE/lang/zh-cn/zh-cn';
	export default {
		props: {
			defaultMsg: {type: String},
			config: {type: Object}
		},
		data() {
			return {
				id: Math.ceil(Math.random()*1000) + 'editor'
			}
		},
		methods: {
			getUEContent() {
				return this.editor.getContent();
			},
			destoryed() {
				return this.editor.destory();
			}

		},
		mounted() {
			let $_this = this;
			this.editor = UE.getEditor(this.id, this.config);
			this.editor.addListener("ready", function() {
				$_this.editor.setContent($_this.defaultMsg);
			});
		},
	}

</script>