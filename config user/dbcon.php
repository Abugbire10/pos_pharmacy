<?php

define('DB_SERVER', "localhost");
define('DB_USERNAME', "root");
define('DB_PASSWORD', "");
define('DB_DATABASE', "pospharmacy");
define('port', "3308");

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE, port);

if (!$conn) {
    die("connection Failed:". mysqli_connect_error());
}

?>