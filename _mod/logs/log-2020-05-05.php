<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-05-05 11:11:26 --> Could not find the language line "auth_title"
ERROR - 2020-05-05 11:11:29 --> Could not find the language line "auth_title"
ERROR - 2020-05-05 11:11:29 --> Severity: Warning --> Use of undefined constant _TBL_VIEW_USERS - assumed '_TBL_VIEW_USERS' (this will throw an Error in a future version of PHP) D:\xampp\htdocs\Project\Menlhk\app\_mod\modules\auth\models\Ion_auth_model.php 933
ERROR - 2020-05-05 11:11:29 --> Query error: ERROR:  relation "lhk__TBL_VIEW_USERS" does not exist
LINE 2: FROM "lhk__TBL_VIEW_USERS"
             ^ - Invalid query: SELECT *
FROM "lhk__TBL_VIEW_USERS"
WHERE "username" = 'admin.lhk'
ORDER BY "id" DESC
 LIMIT 1
