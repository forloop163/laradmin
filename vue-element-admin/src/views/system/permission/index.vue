<template>
  <div class="app-container" v-loading="listLoading">
    <el-row style="margin-bottom: 10px">
      <el-col v-show="is_manage" >
        <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="is_manage=false">
          角色配置
        </el-button>
      </el-col>
      <el-col v-show="!is_manage" >
        <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="is_manage=true">
          权限管理
        </el-button>
        <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="onSaveRolePermission">
          保存
        </el-button>
      </el-col>
    </el-row>
    <el-col :span="18" v-if="is_manage">
      <el-tree
        :data="data"
        node-key="id"
        default-expand-all
        draggable
        highlight-current
        :expand-on-click-node="false"
        @node-drop="onNodeDropHandle"
        :render-content="renderContent">
      </el-tree>
    </el-col>
    <el-col :span="18" v-if="!is_manage">
      <el-select v-model="currentRole" @change="onSelectRole" clearable placeholder="请选择">
        <el-option
          v-for="item in rolesList"
          :key="item.id"
          :label="item.name"
          :value="item.id">
        </el-option>
      </el-select>
      <el-tree
        :data="data"
        node-key="id"
        default-expand-all
        check-on-click-node
        show-checkbox
        ref="tree"
        :default-checked-keys="defaultCheckedKeys"
        :expand-on-click-node="false"
        check-strictly
        >
      </el-tree>
    </el-col>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" @close="onCancelHandle">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="80px">
        <el-form-item v-show="dialogStatus === 'create'" label="上级">
          <el-select v-model.number="temp.parent" @change="onParentHandle" placeholder="选择上级">
            <el-option :label="currentData.label" :value="currentData.id"></el-option>
            <el-option :label="rootDir.label" :value="rootDir.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="名称" prop="name">
          <el-input v-model.trim="temp.name" placeholder="user(英文小写)"></el-input>
        </el-form-item>
        <el-form-item label="显示" prop="label">
          <el-input v-model.trim="temp.label" placeholder="系统管理"></el-input>
        </el-form-item>
        <el-form-item label="路径" prop="path">
          <el-input v-model.trim="temp.path" placeholder="/system"></el-input>
        </el-form-item>
        <el-form-item v-show="showRedirect" label="跳转" prop="redirect">
          <el-input v-model.trim="temp.redirect" placeholder="noRedirect/tab按钮跳转路径"></el-input>
        </el-form-item>
        <el-form-item label="icon">
          <el-input v-model.trim="temp.meta.icon" placeholder="图标"></el-input>
        </el-form-item>
        <el-form-item label="是否展示">
          <el-radio-group v-model.number="temp.display">
            <el-radio :label="0">否</el-radio>
            <el-radio :label="1">是</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="组件">
          <el-input v-model.trim="temp.component" placeholder="system/user/index"></el-input>
        </el-form-item>
        <el-form-item label="是否Api">
          <el-radio-group v-model.number="temp.is_api">
            <el-radio :label="0">否</el-radio>
            <el-radio :label="1">是</el-radio>
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

export default {
    data() {
      return {
        listLoading: false,
        data: [],
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
            icon: ''
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
        showRedirect: false,
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
        dialogFormVisible: false
      };
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
            duration: 2000
          })
          return false;
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
              duration: 2000
            })
            this.httpGet()
          })
        }).catch(() => {
          this.$notify({
            title: '通知',
            message: '已取消删除',
            type: 'success',
            duration: 2000
          })
        });
      },
      onNodeDropHandle(draggingNode, dropNode, dropType, ev) {
        let params = {
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
          <span class="custom-tree-node">
            <span>{node.label}</span>
            <span>
              <el-button size="mini" type="text" on-click={ () => this.append(data) }>新增</el-button>
              <el-button size="mini" type="text" on-click={ () => this.edit(data) }>编辑</el-button>
              <el-button size="mini" type="text" on-click={ () => this.remove(node, data) }>删除</el-button>
            </span>
          </span>);
      },
      onCreateSubmit(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.temp.meta.title = this.temp.label
            createPermission(this.temp).then(response => {
              this.httpGet()
              this.dialogFormVisible = false;
              this.$notify({
                title: '通知',
                message: '创建成功',
                type: 'success',
                duration: 2000
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
              this.dialogFormVisible = false;
              this.$notify({
                title: '通知',
                message: '修改成功',
                type: 'success',
                duration: 2000
              })
            })
          }
        })
      },
      edit(data) {
        this.dialogStatus = 'edit'
        this.dialogFormVisible = true;
        this.temp = data;
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
            icon: ''
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
        this.dialogFormVisible = false;
      },
      onSelectRole(val) {
        fetchRole(val).then(response => {
          const ids = _.pluck(response.data.permissions, 'id')
          this.$refs.tree.setCheckedKeys(ids);
        })
      },
      onSaveRolePermission() {
        setPermission(this.currentRole, {permissions: this.$refs.tree.getCheckedKeys()}).then(response => {
          this.$notify({
            title: '通知',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
        })
      },
      onParentHandle(val) {
        if (val === 0) {
          this.temp.component = 'Layout'
          this.showRedirect = true
        } else {
          this.temp.component = ''
          this.showRedirect = false
        }
      }
    }
  };
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
</style>
