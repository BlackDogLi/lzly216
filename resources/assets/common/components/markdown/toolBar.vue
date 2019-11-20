<template>
    <div class="toolbar-container">
        <div class="mark-container">
            <ul class="toolList">
                <li class="toolItem" @click="addElement('Strong', true)">B</li>
                <li class="toolItem" @click="addElement('Italic', true)"><i>I</i></li>
                <li class="toolItem" @click="addElement('Strikethrough', true)"><i class="fa fa-strikethrough" aria-hidden="true"></i></li>
                <li class="toolItem" @click="addElement('H1')">H1</li>
                <li class="toolItem" @click="addElement('H2')">H2</li>
                <li class="toolItem" @click="addElement('H3')">H3</li>
                <li class="toolItem" @click="addElement('H4')">H4</li>
                <li class="toolItem" @click="addElement('H5')">H5</li>
                <li class="toolItem" @click="addElement('H6')">H6</li>
                <li class="toolItem" @click="addElement('Line')">-</li>
                <li class="toolItem" @click="addElement('Quote', true)"><i class="fa fa-quote-left" aria-hidden="true"></i></li>
                <li class="toolItem" @click="addElement('Code', true)"><i class="fa fa-code" aria-hidden="true"></i></li>
                <li class="toolItem" @click=""><i class="fa fa-link" aria-hidden="true"></i></li>
                <li class="toolItem" @click="imgAdd()">
                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                    <input type="file" ref="inputFile" accept="image/jpeg,image/jpg,image/png" multiple="multiple" @change="imgUpload" style="display: none"/>
                </li>
                <li class="toolItem" @click="addElement('Table')"><i class="fa fa-table" aria-hidden="true"></i></li>
                <li class="toolItem" @click="addElement('U1')"><i class="fa fa-list-ul" aria-hidden="true"></i></li>
                <li class="toolItem" @click="addElement('O1')"><i class="fa fa-list-ol" aria-hidden="true"></i></li>
                <li class="toolItem" @click="tbClick('fullPageFn')"><i class="fa fa-arrows-alt" aria-hidden="true"></i></li>
                <li class="toolItem" @click="tbClick('previewFn')"><i class="fa fa-eye-slash" aria-hidden="true"></i></li>
                <li class="toolItem" @click="tbClick('preAllFn')"><i class="fa fa-eye" aria-hidden="true"></i></li>
                <li class="toolItem" @click="" title="帮助"><i class="fa fa-question-circle-o" aria-hidden="true"></i></li>
            </ul>
        </div>
    </div>
</template>

<script type="text/ecmascript-6" >

export default {
    name: 'markdown-toolBar',
    props:{},
    data () {
        return {
        }
    },
    computed: {},
    methods: {
        tbClick: function (handle){
            this.$emit('toolbarClick', handle);
        },
        
        //文件选择框
        imgAdd: function () {
            this.$refs.inputFile.click();
        },
        
        //上传文件
        imgUpload: function (e) {
            let file = e.target.files;
            //校验上传文件是否为图片
            if (file[0].type.match(/^image\//i)) {
                this.$emit('imgUpload',file[0]);
            } else {
                this.$message({
                    message: '请选择正确的图片',
                    type: 'error',
                });
            }
        },

        //添加元素
        addElement: function (element, isRestPos = false) {
            this.$emit('addElement', element, isRestPos);
        },
    }
}

</script>

<style lang="scss">
    .toolbar-container {
        width: 100%;
        height: 100%;
        background: #e6f0f3;
    }
    .mark-container {
        width:100%;
        height: 100%;
        ul.toolList {
            width: auto;
            height: 100%;
            display: flex;
            padding: 0 10px;
            li.toolItem {
                list-style: none;
                width: 20px;
                height: 100%;
                margin: 0 2px;

            }
        }
    }
</style>