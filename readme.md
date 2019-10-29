
## 关于 Laradmin

Laradmin 使用vue-element-admin 和 laravel6搭建的带有后台RBAC权限验证功能的成型后台。

## 手册地址

- [vue-element-admin](https://panjiachen.github.io/vue-element-admin-site/)前端手册
- [laravel6.*](https://learnku.com/docs/laravel/6.x)Laravel6手册

## 安装

- git clone https://github.com/forloop163/laradmin.git
- cp .env.example .env 并修改相关数据库配置
- composer install
- php artisan key:generate
- php artisan migrate 请先注释App\Providers\AuthServiceProvider第28行$this->registerGates();
- cd vue-element-admin / npm install / npm run dev



