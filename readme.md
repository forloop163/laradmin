
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



## 发布
- 前端nginx配置
   ```
   location / {
     try_files $uri $uri/ /index.html;
   }
   ```
- 后端nginx配置
   ```
   server {
       listen 80;
       server_name example.com;
       root /example.com/public;
   
       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-XSS-Protection "1; mode=block";
       add_header X-Content-Type-Options "nosniff";
   
       index index.html index.htm index.php;
   
       charset utf-8;
   
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
   
       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }
   
       error_page 404 /index.php;
   
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   
       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
-       
