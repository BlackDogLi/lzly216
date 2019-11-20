<template>
    <div class="md-container" :class="{fullPage: fullPageStatus}">
        <!-- 工具栏 -->
        <div class="md-toolbar">
            <toolbar @toolbarClick="toolbarClick" @imgUpload="imgUpload" @addElement="addElement"></toolbar>
        </div>

        <div class="md-body">
            <div class="md-editor" v-if="editStatus">
                <textarea name class="mdEditor" v-model="inputContent"></textarea>
            </div>
            <div class="md-preview markdown-body" v-if="previewStatus" v-html="mdData.htmlValue"></div>
        </div>
    </div>
</template>
<script type="text/ecmascript-6">
    import toolbar from './toolBar';
    import marked from 'marked';
    import posFn from './js/posFocus';
    //import vuescroll from 'vuescroll';

    //加载marked配置
    marked.setOptions({
        render: new marked.Renderer(),
        gfm: true,              //允许Git Hub标准
        tables: true,           //支持表格语法
        breaks: false,          //允许回车换行
        pedantic: false,        //禁垦呢个兼容`markdown.pl`的晦涩部分
        sanitize: true,         //对输出进行过滤
        smartLists: true,       //使用比原生markdown更时髦的列表
        smartypants: false,     //使用更为时髦的标点
    });

    function insertContent (val, that)
    {
        let textDom = document.querySelector('.mdEditor');
        let value = textDom.value;
        let point = posFn.getCurrentPosition(textDom);
        let lastChar = value.substring(point - 1, point);
        let lastFourChars = value.substring(point - 4, point);
        if (lastChar != '\n' && value != '' && lastFourChars != '    ') {
            val = '\n' + val;
            posFn.insertTextAfterFocus(textDom, val);
        } else {
            posFn.insertTextAfterFocus(textDom, val)
        }

        that.inputContent = document.querySelector('.mdEditor').value;
    }

    export default {
        name: 'markdown',
        components: {
            toolbar
        },
        props: {
            editContent:{
                type: String,
                default:''
            }
        },
        data(){
            return {
                inputContent:'',
                editStatus:true,
                previewStatus: true,
                fullPageStatus: false,
                imgUrl: '',
                mdData:{
                    mdValue:'',
                    htmlValue: '',
                },
            }
        },
        computed: {
        },
        methods: {
            //工具栏点击事件
            toolbarClick: function (handle) {
               this[handle]();
            },

            //是否显示预览区
            previewFn: function () {
                if (!this.editStatus) {
                    this.editStatus = true;
                    this.previewStatus = !this.previewStatus;
                } else {
                    this.previewStatus = !this.previewStatus;
                }
            },

            //是否全屏
            fullPageFn: function () {
                this.fullPageStatus = !this.fullPageStatus;
            },

            //是否全部显示预览区
            preAllFn: function () {
                if (!this.editStatus && this.previewStatus) {
                    this.editStatus = true;
                    this.previewStatus = true;
                } else {
                    this.editStatus = false;
                    this.previewStatus = true;
                }
            },

            //文章图片上传
            imgUpload: function (file) {
                let data = new FormData();
                data.append('img',file);
                let config = {headers: {'Content-Type': 'application/x-www-form-urlencoded'}};
                this.axios.post('article/articleImg',data).then(function (res) {
                    let data = res.data;
                    if (data.status == 200) {
                        let content = '![](' + data.url + ')';
                        insertContent(content, this);
                    }
                }).catch(function (error){
                    console.log(error);
                });
            },

            //添加标题
            addElement: function (element, isRestPos) {
                let insertValue = '';
                let offset = 0;
                switch (element) {
                    case 'H1':
                        insertValue = '# ';
                        break;
                    case 'H2':
                        insertValue = '## ';
                        break;
                    case 'H3':
                        insertValue = '### ';
                        break;
                    case 'H4':
                        insertValue = '#### ';
                        break;
                    case 'H5':
                        insertValue = '##### ';
                        break;
                    case 'H6':
                        insertValue = '###### ';
                        break;
                    case 'Line':
                        insertValue = '\n----\n';
                        break;
                    case 'Table':
                        insertValue = '\n header 1 | header 2\n --- | --- \n row1 col1 | row1 col2\n row2 col1 | row2 cole2\n\n';
                        break;
                    case 'U1':
                        insertValue = '* ';
                        break;
                    case 'O1':
                        insertValue = '1. '
                        break;
                    case 'Code':
                        insertValue = '```\n\n```';
                        offset = 5;
                        break;
                    case 'Quote':
                        insertValue = '> ';
                        offset = 3;
                        break;
                    case 'Strikethrough':
                        insertValue = '~~~~';
                        offset = 3;
                        break;
                    case 'Strong':
                        insertValue = '****';
                        offset = 3;
                        break;
                    case 'Italic':
                        insertValue = '**';
                        offset = 2;
                        break;
                    default:
                        break;
                }
                //插入内容
                if (isRestPos) {
                    this.resetPosFn(element, insertValue, offset);
                } else {
                    insertContent(insertValue, this);
                }
            },

            //插入内容重新定位光标位置
            resetPosFn: function (element, insertValue = '', offset = 0) {
                let textDom = document.querySelector('.mdEditor');
                let value = textDom.value;
                let point = posFn.getCurrentPosition(textDom);
                let lastChart = value.substring(point - 1, point);

                insertContent(insertValue, this);
                //重置光标
                if (lastChart != '\n' && value != '') {
                    posFn.setFocusPosition(textDom, point + offset);
                } else {
                    posFn.setFocusPosition(textDom, point + offset - 1);
                }

            },

        },
        watch: {
            editContent: function () {
                this.inputContent = this.editContent;
            },
            inputContent: function () {
                this.mdData.mdValue = this.inputContent;
                this.mdData.htmlValue = marked(this.inputContent, {sanitize: true});
                this.$emit('mdValue', this.mdData);
            }
        },
    }
</script>

<style lang="scss">
    .md-container {
        width: 100%;
        height: 100%;
        background: lightblue;
        &.fullPage {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
        }
        .md-toolbar {
            width: 100%;
            height: 36px;
            background: #FFFFFF;
            border-bottom: 1px solid #eeeeee;
        }
        .md-body {
            width: 100%;
            height: calc(100% - 36px);
            background: #FFFFFF;
            display: flex;

            //编辑区
            .md-editor {
                width: 100%;
                border-right: 1px solid #DDDDDD;
                background: #333;
                color: #fff;
                padding: 10px;
                .mdEditor {
                    height: 100%;
                    width: 100%;
                    background: transparent;
                    outline: none;
                    color: #fff;
                    resize: none;
                    border: none;
                }
            }

            //预览区
            .md-preview {
                width: 100%;
                height: 100%;
                background: #fff;
                overflow: auto;
                padding: 10px;
            }
        }
    }
</style>