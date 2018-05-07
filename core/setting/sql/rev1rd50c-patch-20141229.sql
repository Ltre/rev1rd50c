-- @since 2014-12-29
-- HP localhost 已同步
-- Office localhost 已同步
-- heng_mikuus 已同步
ALTER TABLE `csdr_article`
ADD COLUMN `createip`  varchar(16) NULL COMMENT '创建IP';
