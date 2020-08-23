## 在Directory Lister魔改汉化版的基础上加入了文件上传功能。

### 使用前：

* 在hide-upload/server/ 内修改logs/ upload/ upload_tmp/ 这三个文件夹的权限或用户组，让php-fpm拥有这三个文件的读写权限。

* 修改hide-upload/server/upload/resources/config.php 中第3行的密码: $password ，和第4行的网站地址: $webUrl .

* 到这里查看Directory Lister魔改汉化版其他配置说明：[Directory Lister魔改汉化版地址](https://github.com/ToyoDAdoubiBackup/DirectoryLister)
