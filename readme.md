## 基于*laravel5.5* 跟 *layui2.2* 的一个贵州检察院后台管理系统

***
### 项目示例地址
[xxxxxx/admin](xxxxxx/admin)

### 安装步骤

1. 克隆资源库：git clone https://github.com/wangyucai/layui-laravel.git ./
2. 安装依赖关系：composer install
3. 复制配置文件：cp .env.example .env,并进行相关配置
4. 创建新的应用程序密钥：php artisan key:generate
5. 设置数据库：编辑.env文件
	DB_HOST=YOUR_DATABASE_HOST
	DB_DATABASE=YOUR_DATABASE_NAME
	DB_USERNAME=YOUR_DATABASE_USERNAME
	DB_PASSWORD=YOUR_DATABASE_PASSWORD
6. 添加自动加载：composer dump-autoload
7. 运行数据库迁移：php artisan migrate
8. 运行数据填充：php artisan db:seed
9. 配置好环境即可运行

### 注意事项
* 需要环境为php>7。
* 默认配置使用了redis及phpredis扩展，可自行更改相应配置
* 目前仅实现了后台登录及权限功能
* 后台路由: domain/admin

### 感谢
* 后台ui是在BrotherMa的layuicms上稍作修改而来，[layuicms地址](https://github.com/BrotherMa/layuiCMS)
* [layui文档地址](http://www.layui.com/doc/)
* [laravel社区地址](https://laravel-china.org/)

### 
**项目主要是自己学习用**




