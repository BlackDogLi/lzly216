#安装说明:
####(1)确保已安装node.js、composer
####(2)执行命令:
    1)composer install;
    2)npm install;
    3)cp .env.example .env(linux命令)
    4)php artisan migrate(迁移数据表)
    5)php artisan sb:seed(填充数据)
####(3)修改.env文件中数据库连接为本地连接