ALTER TABLE `gmevwspcdt`.`components`     ADD COLUMN `standard_certificate_id` INT(11) NULL COMMENT 'from manage_certificate which type is Standards' AFTER `infonet_status`,     ADD COLUMN `notified_body_certificate` INT(11) NULL COMMENT 'from manage_certificate which type is Notified/certified Body' AFTER `standard_certificate_id`,     ADD COLUMN `article_11b` INT(11) NULL COMMENT 'from manage_certificate which type is Notified/certified Body' AFTER `notified_body_certificate`,     ADD COLUMN `ec_type_certificate_text` VARCHAR(255) NULL AFTER `article_11b`;


ALTER TABLE `gmevwspcdt`.`products`     ADD COLUMN `standard_certificate_id` INT NULL COMMENT 'from manage_certificate which type is Standards' AFTER `product_frequency_asset`,     ADD COLUMN `notified_body_certificate_id` INT NULL COMMENT 'from manage_certificate which type is Notified/certified Body' AFTER `standard_certificate_id`,     ADD COLUMN `article_11b_certificate_id` INT NULL COMMENT 'from manage_certificate which type is Notified/certified Body' AFTER `notified_body_certificate_id`,     ADD COLUMN `ec_type_certificate_text` VARCHAR(255) NULL AFTER `article_11b_certificate_id`;



e Manager » Server » gmevwspcdt