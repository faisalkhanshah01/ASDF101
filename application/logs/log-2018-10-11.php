<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-11 09:21:12 --> Query error: Unknown column 'uacc_device_type' in 'field list' - Invalid query: UPDATE `user_accounts` SET `uacc_device_token` = '123456', `uacc_device_type` = NULL
WHERE `uacc_id` = '352'
ERROR - 2018-10-11 09:28:45 --> Query error: Table 'arresto2018.ar_client_2018' doesn't exist - Invalid query: SELECT `customer_logo_path`, `customer_`
FROM `ar_client_2018`
WHERE `customer_uacc_fk` = '354'
ERROR - 2018-10-11 09:29:28 --> Query error: Unknown column 'customer_' in 'field list' - Invalid query: SELECT `customer_logo_path`, `customer_`
FROM `ar_clients_2018`
WHERE `customer_uacc_fk` = '354'
ERROR - 2018-10-11 12:16:26 --> Severity: Error --> Cannot use object of type CI_DB_mysqli_result as array /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Api_model.php 507
ERROR - 2018-10-11 12:16:52 --> Severity: Error --> Cannot use object of type CI_DB_mysqli_result as array /Applications/MAMP/htdocs/MySites/arresto2018/application/models/Api_model.php 506
ERROR - 2018-10-11 12:50:50 --> Query error: Column 'upp_product_id' cannot be null - Invalid query: INSERT INTO `asm_user_project_products` (`upp_user_id`, `upp_mdata_id`, `upp_product_id`, `upp_project_id`) VALUES ('364', '4614', NULL, '6')
