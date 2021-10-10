# STLLDAY
在线聊天网

PHP版本要求7.4

使用方法:
  1.创建数据库
  
    sql代码(请一行一行输入):
      CREATE TABLE user_message(time char(255),message mediumtext,now int(11))
      ALTER TABLE `user_message` ADD PRIMARY KEY(`time`)
      ALTER TABLE `user_message` ADD INDEX(`time`)
    
  2.在index.php填写好参数
  
  3.以上步骤完成后可直接使用
  

后续会更新好友系统
