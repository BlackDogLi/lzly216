<template>
    <el-table :data="data" :row-style="showTr">
        <el-table-column v-for="(column, index) of columns" :key="column.dataIndex"
                         :label="column.text"  >
            <template slot-scope="scope">
                <span v-if="spaceIconShow(index)" v-for="(space, levelIndex) of scope.row._level" class="ms-tree-space" :key="levelIndex"></span>
                <span v-if="toggleIconShow(index,scope.row)" @click="toggle(scope.$index)">
          <i v-if="!scope.row._expanded" class="el-icon-caret-right" aria-hidden="true"></i>
          <i v-if="scope.row._expanded" class="el-icon-caret-bottom" aria-hidden="true"></i>
        </span>
                <span v-else-if="index===0" class="ms-tree-space"></span>
                {{scope.row[column.dataIndex]}}
            </template>
        </el-table-column>
        <el-table-column align="center" label="操作" v-if="treeType === 'normal'" >
            <template slot-scope="props">
                <el-button type="primary" size="small" plain @click="handleEdit(props.row)">
                  编辑
                </el-button>
                <el-button type="danger" size="small" plain @click="handleDelete(props.row)">
                    删除
                </el-button>
            </template>
        </el-table-column>
    </el-table>
</template>
<script type="text/ecmascript-6">
    import Utils from './tree';
    export default {
        name: "TableTree",
        props: {
            //对父组件传过来的数据进行树形格式化
            treeStructure: {
                type: Boolean,
                default: function () {
                    return false
                }
            },
            //展示对应的字段
            columns: {
                type: Array,
                default: function () {
                    return []
                }
            },
            //数据源
            dataSource: {
                type: Array,
                default: function () {
                    return []
                }
            },
            //是否展示操作列
            treeType: {
                type: String,
                default: function () {
                    return 'normal'
                }
            },
            //是否默认展示所有树
            defaultExpandAll: {
                type: Boolean,
                default: function () {
                    return false
                }
            }
        },
        data () {
            return {}
        },
        computed: {
            //格式化数据源
            data: function () {
                let _self = this;
                if (_self.treeStructure) {
                    let data = Utils.treeToArray(_self.dataSource, null,null, _self.defaultExpandAll)
                    return data
                }
                return _self.dataSource
            }
        },
        methods: {
            //显示行
            showTr(row,index) {
                let show = (row.row._parent ? (row.row._parent._expanded && row.row._parent._show) : true)
                row.row._show = show
                return show ? '' : 'display:none;'
            },
            //展开下级
            toggle (trIndex) {
                let record = this.data[trIndex]
                record._expanded = !record._expanded
            },
            //显示层级关系空格和图标
            spaceIconShow(index) {
                if (this.treeStructure && index === 0) {
                    return true
                }
                return false;
            },
            //点开展示和关闭的时候,图标的切换
            toggleIconShow (index, record) {
                if (this.treeStructure && index === 0 && record.children && record.children.length > 0) {
                    return true
                }
                return false
            },
            //编辑
            handleEdit: function(row){
                this.$emit('edit', row);
            },
            //删除
            handleDelete: function (row) {
                this.$emit('delete', row);
            }
        }
    }
</script>
<style scoped>
    .ms-tree-space{position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        width: 18px;
        height: 14px;}
    .ms-tree-space::before{content: ""}
    table td{
        line-height: 26px;
    }
</style>