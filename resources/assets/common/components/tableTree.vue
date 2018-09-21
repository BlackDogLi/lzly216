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
<script>
    //import Utils from '../../admin/js/lib/util.js'
    import Utils from './tree';
    export default {
        name: "TableTree",
        props: {
            treeStructure: {
                type: Boolean,
                default: function () {
                    return false
                }
            },
            columns: {
                type: Array,
                default: function () {
                    return []
                }
            },
            dataSource: {
                type: Array,
                default: function () {
                    return []
                }
            },
            treeType: {
                type: String,
                default: function () {
                    return 'normal'
                }
            },
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
            data: function () {
                let $_this = this;
                if ($_this.treeStructure) {
                    let data = Utils.treeToArray($_this.dataSource, null,null, $_this.defaultExpandAll)
                    return data
                }
                return $_this.dataSource
            }
        },
        filter: {
            btnType (value) {
                if (value === 'M') {
                    return '菜单'
                } else if (value === 'B') {
                    return '按钮'
                } else {
                    return value
                }
            }
        },
        methods: {
            showTr(row,index) {
                let show = (row.row._parent ? (row.row._parent._expanded && row.row._parent._show) : true)
                row.row._show = show
                return show ? '' : 'display:none;'
            },
            toggle (trIndex) {
                let record = this.data[trIndex]
                record._expanded = !record._expanded
            },
            spaceIconShow(index) {
                if (this.treeStructure && index === 0) {
                    return true
                }
                return false;
            },
            toggleIconShow (index, record) {
                if (this.treeStructure && index === 0 && record.children && record.children.length > 0) {
                    return true
                }
                return false
            },
            handleEdit: function(row){
                this.$emit('edit', row);
            },
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