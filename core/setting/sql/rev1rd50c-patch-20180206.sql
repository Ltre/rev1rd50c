-- @since 2018-02-06
-- HP localhost 已同步
-- Office localhost 已同步
-- linode 已同步
ALTER TABLE `csdr_article`
ADD COLUMN `createip`  varchar(16) NULL COMMENT '创建IP';
