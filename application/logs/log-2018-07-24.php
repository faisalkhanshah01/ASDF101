<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-07-24 07:49:50 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 07:50:24 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 07:50:29 --> 404 Page Not Found: Assets/images
ERROR - 2018-07-24 07:51:27 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:04:32 --> Severity: Parsing Error --> syntax error, unexpected '=>' (T_DOUBLE_ARROW) /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Demo_auth_admin_model.php 25
ERROR - 2018-07-24 08:06:37 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:06:48 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:06:48 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:06:48 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:08 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:08 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:08 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:08 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:08 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:09 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:09 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:24 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:25 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:25 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:25 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:26 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:26 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:26 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:26 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:36 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 08:07:36 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:36 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 08:07:53 --> Severity: Parsing Error --> syntax error, unexpected '*' /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Demo_auth_admin_model.php 28
ERROR - 2018-07-24 08:09:31 --> Severity: Parsing Error --> syntax error, unexpected '*' /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Demo_auth_admin_model.php 28
ERROR - 2018-07-24 08:15:10 --> Severity: Parsing Error --> syntax error, unexpected 'ugrp_pid' (T_STRING), expecting ']' /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Demo_auth_admin_model.php 26
ERROR - 2018-07-24 08:21:08 --> Query error: Unknown column 'id' in 'field list' - Invalid query: SELECT `id`
FROM `user_groups`
WHERE `ugrp_pid` = '1'
ERROR - 2018-07-24 09:19:16 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 09:19:43 --> Query error: Unknown column 'id' in 'where clause' - Invalid query: SELECT `uacc_id`, `uacc_email`, `uacc_suspend`, `ugrp_name`, `upro_first_name`, `upro_last_name`
FROM `user_accounts`
LEFT JOIN `user_groups` ON `user_accounts`.`uacc_group_fk` = `user_groups`.`ugrp_id`
LEFT JOIN `demo_user_profiles` ON `user_accounts`.`uacc_id` = `demo_user_profiles`.`upro_uacc_fk`
LEFT JOIN `demo_user_address` ON `user_accounts`.`uacc_id` = `demo_user_address`.`uadd_uacc_fk`
WHERE `id` IN('2', '3', '13', '14', '1')
GROUP BY `user_accounts`.`uacc_id`
ERROR - 2018-07-24 09:21:37 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:21:37 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:25:59 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:25:59 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:27:09 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:27:09 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:28:58 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:28:58 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:33:33 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:33:33 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:39:48 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:39:49 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:39:59 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:39:59 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 09:39:59 --> 404 Page Not Found: Includes/images
ERROR - 2018-07-24 09:42:29 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 09:42:29 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:03:58 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:04:15 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:04:15 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:04:17 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:04:48 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:04:53 --> Severity: Error --> Call to undefined method Auth_admin::show_dashboard() /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/Auth_admin.php 105
ERROR - 2018-07-24 10:05:01 --> 404 Page Not Found: Assets/images
ERROR - 2018-07-24 10:05:18 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:05:22 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:05:28 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:07:16 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:08:17 --> 404 Page Not Found: Uploads/images
ERROR - 2018-07-24 10:08:26 --> 404 Page Not Found: Uploads/images
