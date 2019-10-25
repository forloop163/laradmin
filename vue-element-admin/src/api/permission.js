import request from '@/utils/request'

export function getPermission() {
  return request({
    url: '/system/permissions',
    method: 'get',
  })
}

export function createPermission(data) {
  return request({
    url: '/system/permissions',
    method: 'post',
    data
  })
}

export function updatePermission(id, data) {
  return request({
    url: '/system/permissions/' + id,
    method: 'put',
    data
  })
}

export function deletePermission(id) {
  return request({
    url: '/system/permissions/' + id,
    method: 'delete',
  })
}

export function nodeDrop(data) {
  return request({
    url: '/system/permissions/node_drop',
    method: 'post',
    data
  })
}



