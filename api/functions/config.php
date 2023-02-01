<?php
// Global configs
define('DEBUG_QUERY', false);
define('DEBUG_PHP', false);

//Production
define('DB1_DRIVE', 'pgsql');
define('DB1_HOSTNAME', 'localhost');
define('DB1_PORT', '5432');
define('DB1_DATABASE', 'projetodevio');
define('DB1_USERNAME', 'postgres');
define('DB1_PASSWORD', '16121997vi');

//Devel
define('DB2_DRIVE', 'pgsql');
define('DB2_HOSTNAME', 'localhost');
define('DB2_PORT', '5432');
define('DB2_DATABASE', 'projetodevio');
define('DB2_USERNAME', 'postgres');
define('DB2_PASSWORD', 'teste');

define('LOGIN_TIME_EXPIRE_TOKEN', '60 minutes');

define('CONFIG_URL', 'http://localhost'); 
define('BUCKET', 'projetodevio');
define('CLOUD', 'amazon');