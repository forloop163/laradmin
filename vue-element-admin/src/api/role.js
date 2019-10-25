import request from '@/utils/request'

export function fetchRoles() {
  return request({
    url: '/system/role_dict',
    method: 'get',
  })
}

export function fetchList(query) {
  return request({
    url: '/system/roles',
    method: 'get',
    params: query
  })
}

export function fetchRole(id) {
  return request({
    url: '/system/roles/' + id,
    method: 'get',
  })
}

export function createRole(data) {
  return request({
    url: '/system/roles',
    method: 'post',
    data
  })
}

export function updateRole(id, data) {
  return request({
    url: `/system/roles/` + id,
    method: 'put',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: '/system/roles/' + id,
    method: 'delete',
  })
}

export function resetRole(id) {
  return request({
    url: '/system/roles/' + id + '/reset',
    method: 'put',
  })
}

export function freezeRole(id) {
  return request({
    url: '/system/roles/' + id + '/freeze',
    method: 'put',
  })
}

export function setPermission(id, data) {
  return request({
    url: '/system/role_permission/' + id,
    method: 'put',
    data
  })
}

