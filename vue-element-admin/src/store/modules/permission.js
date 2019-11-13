import { asyncRoutes, constantRoutes } from '@/router'
import Layout from '@/layout'

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
        const tmp = { ...item }
        if (item.component === 'Layout') {
            tmp.component = Layout
        } else {
            let sub_view = tmp.component
            if (!sub_view) {
                const message = 'Error Component' + tmp.name
                throw message
            }
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
    generateRoutes({ commit }, permissions) {
        return new Promise(resolve => {
            const accessedRoutes = asyncRoutes.concat(dataArrayToRoutes(permissions))
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
