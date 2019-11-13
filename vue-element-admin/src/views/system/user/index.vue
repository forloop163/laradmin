<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model.trim="listQuery.username" placeholder="用户名称" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="listQuery.active" placeholder="用户状态" clearable style="width: 200px" class="filter-item">
        <el-option v-for="active in actives" :key="active.value" :label="active.label" :value="active.value" />
      </el-select>
      <el-input v-model.trim="listQuery.mobile" placeholder="手机号码" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-input v-model.trim="listQuery.email" placeholder="用户邮箱" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        新增
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @sort-change="sortChange"
    >
      <el-table-column
        prop="id"
        label="ID"
        width="80px"
        align="center"
        sortable="custom"
        >
      </el-table-column>
      <el-table-column
        prop="username"
        label="用户名称"
        >
      </el-table-column>
      <el-table-column
        prop="mobile"
        label="手机号码"
        >
      </el-table-column>
      <el-table-column
        prop="email"
        show-overflow-tooltip
        label="邮箱号码"
        >
      </el-table-column>
      <el-table-column
        prop="last_login"
        label="最后登陆时间"
        sortable="custom"
        >
      </el-table-column>
      <el-table-column label="操作" align="center" width="270px" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            编辑
          </el-button>
          <el-button type="danger" size="mini" @click="handleDelete(row)">
            删除
          </el-button>
          <el-button v-if="row.active!=1" size="mini" type="success" @click="handleResetUser(row)">
            恢复
          </el-button>
          <el-button v-if="row.active==1" size="mini" @click="handleFreezeUser(row)">
            冻结
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-col style="text-agline">
      <el-pagination
        style="text-align: center; margin-top:10px;"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="current_page"
        :page-sizes="[20, 40, 100, 200]"
        :page-size="listQuery.pageSize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total">
      </el-pagination>
    </el-col>

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="80px">
        <el-form-item label="用户名称" prop="username">
          <el-input v-model="temp.username" />
        </el-form-item>
        <el-form-item label="手机号码" prop="mobile">
          <el-input v-model="temp.mobile" />
        </el-form-item>
        <el-form-item label="邮箱地址" prop="email">
          <el-input v-model="temp.email" />
        </el-form-item>
        <el-form-item label="角色" prop="roles">
          <el-select v-model="temp.roles" multiple clearable placeholder="请选择">
            <el-option
              v-for="item in rolesList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="dialogStatus==='create'" label="密码" prop="password" :rules="[
            { required: true, message: '请输入密码', trigger: 'blur' },
            { min: 6, max: 30, message: '长度必须大于6个字符', trigger: 'blur' }
          ]">
          <el-input v-model="temp.password" />
        </el-form-item>
        <el-form-item v-if="dialogStatus==='update'" label="密码" prop="password" :rules="[
            { min: 6, max: 30, message: '长度必须大于6个字符', trigger: 'blur' }
          ]">
          <el-input v-model="temp.password"  placeholder="不修改请置空" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">
          取消
        </el-button>
        <el-button type="primary" @click="dialogStatus==='create'?createData():updateData()">
          确认
        </el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchList, fetchUser, createUser, updateUser, deleteUser, resetUser, freezeUser } from '@/api/user'
import { fetchRoles } from '@/api/role'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination
import _ from 'underscore'

const actives = [
  { value: 0, label: '禁用'},
  { value: 1, label: '正常'},
];

export default {
  name: 'User',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        0: '禁用',
        1: '正常',
      }
      return statusMap[status]
    },
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      total: 0,
      current_page: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        pageSize: 20
      },
      actives,
      temp: {
        id: undefined,
        active: 1,
        username: '',
        email: '',
        mobile: '',
        roles: []
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '新增'
      },
      rules: {
        username: [{ required: true, message: '用户名称必须填写', trigger: 'change' }],
        mobile: [{ required: true, message: '手机号码必须填写', trigger: 'change' }],
        email: [{ required: true, message: '邮箱必须填写', trigger: 'change' },
          { type: 'email', message: '请输入正确的邮箱地址', trigger: 'change' }],
      },
      rolesList: []
    }
  },
  created() {
    this.getList()
    this.getRoles()
  },
  methods: {
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.data
        this.total = response.data.total
        this.current_page = response.data.current_page
        this.listLoading = false
      })
    },
    getRoles() {
      fetchRoles().then(response => {
        this.rolesList = response.data
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    handleSizeChange(val) {
      this.listQuery.pageSize = val
      this.getList()
    },
    handleCurrentChange(val) {
      this.listQuery.page = val
      this.getList()
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: '操作Success',
        type: 'success'
      })
      row.status = status
    },
    sortChange({order, prop}) {
      this.listQuery.sort = { order, prop };
      this.handleFilter()
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        active: 1,
        username: '',
        email: '',
        mobile: '',
        roles: []
      }
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          createUser(this.temp).then(() => {
            this.dialogFormVisible = false
            this.$notify({
              title: '通知',
              message: '操作成功',
              type: 'success',
              duration: 2000
            })
            this.getList()
          })
        }
      })
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.roles = _.pluck(this.temp.roles, 'id')
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateUser(tempData.id, tempData).then(() => {
            this.dialogFormVisible = false
            this.$notify({
              title: '通知',
              message: '操作成功',
              type: 'success',
              duration: 2000
            })
            this.getList()
          })
        }
      })
    },
    handleDelete(row) {
      this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteUser(row.id).then(response => {
          this.$notify({
            title: '通知',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
          this.getList()
        }).catch(() => {
          this.$notify({
            title: '通知',
            message: '操作失败',
            type: 'danger',
            duration: 2000
          })
        });
      })
    },
    handleResetUser(row) {
      resetUser(row.id).then(response => {
        this.$notify({
          title: '通知',
          message: '操作成功',
          type: 'success',
          duration: 2000
        })
        this.getList()
      }).catch(() => {
        this.$notify({
          title: '通知',
          message: '操作失败',
          type: 'danger',
          duration: 2000
        })
      })
    },
    handleFreezeUser(row) {
      freezeUser(row.id).then(response => {
        this.$notify({
          title: '通知',
          message: '操作成功',
          type: 'success',
          duration: 2000
        })
        this.getList()
      }).catch(() => {
        this.$notify({
          title: '通知',
          message: '操作失败',
          type: 'danger',
          duration: 2000
        })
      })
    }
  }
}
</script>
