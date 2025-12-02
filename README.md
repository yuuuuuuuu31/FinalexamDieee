# FinalexamDieee
INSERT INTO user (account, password, name, role, created_at)
VALUES ('admin', '123456', '管理員', 'admin', NOW());

ALTER TABLE `repair`
ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0:待處理 1:處理中 2:已完成';
