<?php

define('DB_HOST', 'localhost'); // сервер БД
define('DB_USER', 'root'); // логин БД
define('DB_PASS', ''); // пароль БД
define('DB_NAME', 'oballru'); // имя БД

if (!$conn = mysql_connect(DB_HOST,DB_USER,DB_PASS)) {
    echo 'не могу подключиться к серверу БД';
        exit;
}
if (!mysql_select_db(DB_NAME)) {
    echo 'не могу подключить БД';
        exit;
}

?>
