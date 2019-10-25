import { asyncRoutes, constantRoutes } from '@/router'
import Layout from '@/layout'

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []
  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

function dataArrayToRoutes(data) {
  const res = []
  data.forEach(item => {
    let tmp = {...item}
    if (item.component === 'Layout') {
      tmp.component = Layout
    } else {
      let sub_view = tmp.component
      sub_view = sub_view.replace(/^\/*/g, '')
      tmp.component = () => import(`@/views/${sub_view}`)    
    }
    if (tmp.children) {
      tmp.children = dataArrayToRoutes(tmp.children)
    } else {
      tmp.children = []
    }
    res.push(tmp)
  })
  return res
}

const actions = {
  generateRoutes({ commit }, {roles, permissions}) {
    return new Promise(resolve => {
      let accessedRoutes
      if (roles.includes('admin')) {
        accessedRoutes = asyncRoutes || []
      } else {
        accessedRoutes = filterAsyncRoutes(asyncRoutes.concat(dataArrayToRoutes(permissions)), roles)
      }
      commit('SET_ROUTES', accessedRoutes)
      resolve(accessedRoutes)
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
