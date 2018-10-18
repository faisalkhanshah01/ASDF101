<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-09-26 09:53:53 --> 404 Page Not Found: Images/system
ERROR - 2018-09-26 09:53:53 --> 404 Page Not Found: Images/system
ERROR - 2018-09-26 09:56:24 --> 404 Page Not Found: admin/Auth_admin/register_account
ERROR - 2018-09-26 10:20:18 --> Severity: Notice --> Undefined variable: setting /Applications/MAMP/htdocs/MySites/arresto2018/application/controllers/admin/Auth.php 43
ERROR - 2018-09-26 10:46:49 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Flexi_auth_model.php:245) /Applications/MAMP/htdocs/MySites/arresto2018/system/helpers/url_helper.php 564
ERROR - 2018-09-26 12:08:01 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Flexi_auth_model.php:245) /Applications/MAMP/htdocs/MySites/arresto2018/system/helpers/url_helper.php 564
ERROR - 2018-09-26 12:21:06 --> Query error: Table 'client_accounts.uacc_id' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `client_accounts`.`uacc_id`
WHERE (uacc_email = 'reema16@mail.com' OR uacc_username = 'reema16@mail.com')
ERROR - 2018-09-26 12:24:06 --> Query error: Unknown column 'user_accounts.uacc_username' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `client_accounts`
WHERE `user_accounts`.`uacc_username` = 'reema16'
ERROR - 2018-09-26 12:31:51 --> Severity: Notice --> Undefined property: CI_Config::$uri /Applications/MAMP/htdocs/MySites/arresto2018/application/config/flexi_auth.php 55
ERROR - 2018-09-26 12:31:51 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Applications/MAMP/htdocs/MySites/arresto2018/system/core/Exceptions.php:272) /Applications/MAMP/htdocs/MySites/arresto2018/system/core/Common.php 565
ERROR - 2018-09-26 12:31:51 --> Severity: Error --> Call to a member function segment() on null /Applications/MAMP/htdocs/MySites/arresto2018/application/config/flexi_auth.php 55
ERROR - 2018-09-26 12:36:24 --> Query error: Table 'arresto2018.user_clients' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `user_clients`
WHERE (uacc_email = 'reema16@mail.com' OR uacc_username = 'reema16@mail.com')
