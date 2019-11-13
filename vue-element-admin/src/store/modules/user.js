import { login, logout, getInfo } from '@/api/user'
import { getToken, setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'

const state = {
    token: getToken(),
    user: {},
    permissions: []
}

const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token
    },
    SET_USER: (state, user) => {
        state.user = user
    },
    SET_PERMISSIONS: (state, permissions) => {
        state.permissions = permissions
    }
}

const actions = {
    // user login
    login({ commit }, userInfo) {
        const { username, password } = userInfo
        return new Promise((resolve, reject) => {
            login({ username: username.trim(), password: password }).then(response => {
                const { data } = response
                const token = data.username
                commit('SET_TOKEN', token)
                setToken(token)
                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    // get user info
    getInfo({ commit, state }) {
        return new Promise((resolve, reject) => {
            getInfo(state.token).then(response => {
                const { code, data } = response

                if (code !== 200 || !data) {
                    reject('验证失败,请重新登录.')
                }
                const { user, permissions } = data

                // roles must be a non-empty array
                if (!user) {
                    reject('没有登录权限!')
                }

                commit('SET_USER', user)
                commit('SET_PERMISSIONS', permissions)
                resolve(data)
            }).catch(error => {
                reject(error)
            })
        })
    },

    // user logout
    logout({ commit, state, dispatch }) {
        return new Promise((resolve, reject) => {
            logout().then(() => {
                commit('SET_TOKEN', '')
                removeToken()
                resetRouter()

                // reset visited views and cached views
                // to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
                dispatch('tagsView/delAllViews', null, { root: true })

                resolve()
            }).catch(error => {
                reject(error)
            })
        })
    },

    // remove token
    resetToken({ commit }) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '')
            removeToken()
            resolve()
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}
