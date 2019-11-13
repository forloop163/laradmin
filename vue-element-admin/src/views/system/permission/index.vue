<template>
  <div v-loading="listLoading" class="app-container">
    <el-row style="margin-bottom: 10px">
      <el-tabs v-model="activeName" type="card" @tab-click="handleActiveClick">
        <el-tab-pane label="权限配置" name="second">
          <div v-loading="secondLoading">
            <el-row class="permission-row permission-config-row">
              <el-select v-model="currentRole" clearable placeholder="请选择" @change="onSelectRole">
                <el-option
                        v-for="item in rolesList"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                />
              </el-select>
              <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="onSaveRolePermission">
                保存
              </el-button>
            </el-row>
            <el-tree
                    ref="tree"
                    :data="data"
                    node-key="id"
                    default-expand-all
                    check-on-click-node
                    show-checkbox
                    :default-checked-keys="defaultCheckedKeys"
                    :expand-on-click-node="false"
                    check-strictly
            />
          </div>
        </el-tab-pane>
        <el-tab-pane label="权限管理" name="first">
          <div class="clearfix">
            <el-tree
                    :data="data"
                    node-key="id"
                    default-expand-all
                    draggable
                    highlight-current
                    :expand-on-click-node="false"
                    :render-content="renderContent"
                    @node-drop="onNodeDropHandle"
            />
          </div>
        </el-tab-pane>
      </el-tabs>
    </el-row>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" @close="onCancelHandle">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="80px">
        <el-form-item v-show="dialogStatus === 'create'" label="上级">
          <el-select v-model.number="temp.parent" placeholder="选择上级">
            <el-option :label="currentData.label" :value="currentData.id" />
            <el-option :label="rootDir.label" :value="rootDir.value" />
          </el-select>
        </el-form-item>
        <el-form-item label="名称" prop="name">
          <el-input v-model.trim="temp.name" placeholder="user(英文小写)" />
        </el-form-item>
        <el-form-item label="显示" prop="label">
          <el-input v-model.trim="temp.label" placeholder="系统管理" />
        </el-form-item>
        <el-form-item label="路径" prop="path">
          <el-input v-model.trim="temp.path" placeholder="/system" />
        </el-form-item>
        <el-form-item label="跳转" prop="redirect">
          <el-input v-model.trim="temp.redirect" placeholder="noRedirect/tab按钮跳转路径" />
        </el-form-item>
        <el-form-item label="icon">
          <el-input v-model.trim="temp.meta.icon" placeholder="图标" />
        </el-form-item>
        <el-form-item label="是否展示">
          <el-radio-group v-model.number="temp.display">
            <el-radio v-for="o in option" :key="o.value" :label="o.value">{{ o.label }}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="隐藏菜单">
          <el-radio-group v-model.number="temp.meta.closeSidebar">
            <el-radio v-for="o in option" :key="o.value" :label="o.value">{{ o.label }}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="组件">
          <el-input v-model.trim="temp.component" placeholder="system/user/index" />
        </el-form-item>
        <el-form-item label="是否Api">
          <el-radio-group v-model.number="temp.is_api">
            <el-radio v-for="o in option" :key="o.value" :label="o.value">{{ o.label }}</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="onCancelHandle">
          取消
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?onCreateSubmit('dataForm'):onEditSubmit('dataForm')">
          确认
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
    import { getPermission, createPermission, updatePermission, deletePermission, nodeDrop } from '@/api/permission'
    import { fetchRoles, fetchRole, setPermission } from '@/api/role'
    import _ from 'underscore'

    const option = [
        {
            label: '是',
            value: 1
        },
        {
            label: '否',
            value: 0
        }
    ]
    export default {
        data() {
            return {
                listLoading: false,
                data: [],
                option: option,
                rootDir: {
                    label: '根目录',
                    value: 0
                },
                dialogStatus: '',
                is_manage: true,
                textMap: {
                    update: '编辑',
                    create: '新增'
                },
                temp: {
                    id: undefined,
                    name: '',
                    label: '',
                    parent: 0,
                    meta: {
                        title: '',
                        icon: '',
                        closeSidebar: 0
                    },
                    path: '',
                    redirect: '',
                    component: '',
                    display: 1,
                    is_api: 0
                },
                currentRole: '',
                currentData: {}, // 操作行数据
                defaultCheckedKeys: [],
                rolesList: [],
                rules: {
                    name: [
                        { required: true, message: '名称必须填写', trigger: 'blur' },
                        { min: 3, max: 50, message: '长度在 3 到 50 个字符', trigger: 'blur' }
                    ],
                    label: [
                        { required: true, message: '显示名称必须填写', trigger: 'blur' },
                        { min: 3, max: 50, message: '长度在 3 到 50 个字符', trigger: 'blur' }
                    ]
                },
                dialogFormVisible: false,
                activeName: 'second',
                tableTree: [],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                secondLoading: false
            }
        },
        created() {
            this.httpGet()
            this.getRoles()
        },
        methods: {
            httpGet() {
                this.listLoading = true
                getPermission().then(response => {
                    this.data = response.data
                    this.listLoading = false
                })
            },
            getRoles() {
                fetchRoles().then(response => {
                    this.rolesList = response.data
                })
            },
            handleActiveClick() {
                this.currentRole = ''
                if (this.activeName === 'second') {
                    this.$refs.tree.setCheckedKeys([])
                }
            },
            append(data) {
                this.dialogFormVisible = true
                this.dialogStatus = 'create'
                this.currentData = data
                this.temp.parent = data.id
                this.$nextTick(() => {
                    this.$refs['dataForm'].clearValidate()
                })
            },
            remove(node, data) {
                const children = data.children
                if (children.length > 0) {
                    this.$notify({
                        title: '通知',
                        message: '删除失败',
                        type: 'danger',
                        duration: 2000,
                        offset: 60
                    })
                    return false
                }
                this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    deletePermission(data.id).then(response => {
                        this.$notify({
                            title: '通知',
                            message: '删除成功',
                            type: 'success',
                            duration: 2000,
                            offset: 60
                        })
                        this.httpGet()
                    })
                }).catch(() => {
                    this.$notify({
                        title: '通知',
                        message: '已取消删除',
                        type: 'success',
                        duration: 2000,
                        offset: 60
                    })
                })
            },
            onNodeDropHandle(draggingNode, dropNode, dropType, ev) {
                const params = {
                    'draggingNode': draggingNode.data,
                    'dropNode': dropNode.data,
                    'dropType': dropType
                }
                nodeDrop(params).then(response => {
                    this.httpGet()
                })
            },
            renderContent(h, { node, data, store }) {
                return (
                    <span class='custom-tree-node'>
                    <span>{node.label}</span>
                <span>
                <el-button size='mini' type='text' on-click={ () => this.append(data) }>新增</el-button>
                <el-button size='mini' type='text' on-click={ () => this.edit(data) }>编辑</el-button>
                <el-button size='mini' type='text' on-click={ () => this.remove(node, data) }>删除</el-button>
                </span>
                </span>)
            },
            onCreateSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.temp.meta.title = this.temp.label
                        if (this.temp.is_api === 0 && !this.temp.component) {
                            return this.$notify({
                                title: '通知',
                                message: '请填写component',
                                type: 'warning',
                                duration: 2000,
                                offset: 60
                            })
                        }
                        createPermission(this.temp).then(response => {
                            this.httpGet()
                            this.dialogFormVisible = false
                            this.$notify({
                                title: '通知',
                                message: '创建成功',
                                type: 'success',
                                duration: 2000,
                                offset: 60
                            })
                        })
                    }
                })
            },
            onEditSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.temp.meta.title = this.temp.label
                        updatePermission(this.temp.id, this.temp).then(response => {
                            this.httpGet()
                            this.dialogFormVisible = false
                            this.$notify({
                                title: '通知',
                                message: '修改成功',
                                type: 'success',
                                duration: 2000,
                                offset: 60
                            })
                        })
                    }
                })
            },
            edit(data) {
                this.dialogStatus = 'edit'
                this.dialogFormVisible = true
                this.temp = data
                this.$nextTick(() => {
                    this.$refs['dataForm'].clearValidate()
                })
            },
            resetData() {
                this.temp = {
                    id: undefined,
                    name: '',
                    label: '',
                    parent: 0,
                    meta: {
                        title: '',
                        icon: '',
                        closeSidebar: 0
                    },
                    path: '',
                    component: '',
                    display: 1,
                    redirect: '',
                    is_api: 0
                }
            },
            onCancelHandle() {
                this.resetData()
                this.dialogFormVisible = false
            },
            onSelectRole(val) {
                if (this.activeName === 'second') {
                    this.secondLoading = true
                    fetchRole(val).then(response => {
                        const ids = _.pluck(response.data.permissions, 'id')
                        this.$refs.tree.setCheckedKeys(ids)
                        this.secondLoading = false
                    })
                }
            },
            onSaveRolePermission() {
                if (!this.currentRole) {
                    this.$notify({
                        title: '通知',
                        message: '请选择角色',
                        type: 'warning',
                        duration: 2000,
                    })
                    return
                }
                setPermission(this.currentRole, { permissions: this.$refs.tree.getCheckedKeys() }).then(response => {
                    this.$notify({
                        title: '通知',
                        message: '操作成功',
                        type: 'success',
                        duration: 2000,
                    })
                })
            },
        }
    }
</script>

<style>
  .custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
  }
  .permission-row {
    margin-bottom: 20px;
  }
</style>
