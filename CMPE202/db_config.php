<?php
    define('DB_SERVER', '127.0.0.1');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '123456');
    // define('DB_PASSWORD', '12345678');
    define('DB_DATABASE', 'here_we_go');
    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if (!$db) {
        die("connect to MySQL error: " . mysqli_connect_error());
    }
?>