import axios from 'axios'
import {MessageBox, Message} from 'element-ui'
import store from '@/store'
import {getToken} from '@/utils/auth'

// create an axios instance
const service = axios.create({
    baseURL: process.env.VUE_APP_BASE_API,
    timeout: 5000
})

// request interceptor
service.interceptors.request.use(
    config => {
        if (config.method === 'get') {
            config.params = {
                _t: Date.parse(new Date()) / 1000,
                ...config.params
            }
        }
        return config
    },
    error => {
        console.log(err.response)
        return Promise.reject(error)
    }
)

// response interceptor
service.interceptors.response.use(
    response => {
        const res = response.data
        if (res.code !== 200) {
            // HTTP_UNAUTHORIZED
            if (res.code === 401) {
                MessageBox.confirm('您已经登出, 点击取消将留在当前页面, 否则将重新登陆', '确定登出', {
                    confirmButtonText: '重新登陆',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    store.dispatch('user/resetToken').then(() => {
                        location.reload()
                    })
                })
            } else {
                Message({
                    message: res.message || 'Error',
                    type: 'error',
                    duration: 5 * 1000
                })
                return Promise.reject(new Error(res.message || 'Error'))
            }
        }
        return res
    },
    error => {
        console.log(error.response)
        Message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000
        })
        return Promise.reject(error)
    }
)

export default service
