import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login',
    method: 'post',
    data
  })
}

export function getInfo() {
    return request({
        url: '/auth/user/info',
        method: 'get'
    })
}

export function logout() {
  return request({
    url: '/logout',
    method: 'post'
  })
}

export function fetchList(query) {
  return request({
    url: '/system/users',
    method: 'get',
    params: query
  })
}

export function fetchUser() {
  return request({
    url: '/system/users/' + id,
    method: 'get',
    data
  })
}

export function createUser(data) {
  return request({
    url: '/system/users',
    method: 'post',
    data
  })
}

export function updateUser(id, data) {
  return request({
    url: `/system/users/` + id,
    method: 'put',
    data
  })
}

export function deleteUser(id) {
  return request({
    url: '/system/users/' + id,
    method: 'delete',
  })
}

export function resetUser(id) {
  return request({
    url: '/system/users/' + id + '/reset',
    method: 'put',
  })
}

export function freezeUser(id) {
  return request({
    url: '/system/users/' + id + '/freeze',
    method: 'put',
  })
}
