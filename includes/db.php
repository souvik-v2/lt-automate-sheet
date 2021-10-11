<?php
// Change this to your connection info.
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '');
define('DB_DATABASE', 'lt-automate-sheet');

require('function.php');
// make a connection to the database... now
tep_db_connect() or die('Unable to connect to database server!');
?>