<?php

define('ACCESS', true);
define('PHPMYADMIN', true);

require '.init.php';

$title = 'Ngắt kết nối database';

require 'database_connect.php';
require 'header.php';

if (IS_CONNECT) {
    if ($databases['is_auto']) {
        $databases['is_auto'] = false;
        
        if (!createDatabaseConfig($databases)) {
            echo '<div class="title">' . $title . '</div>
            <div class="list">Ngắt kết nối thất bại</div>
            <div class="title">Chức năng</div>
            <ul class="list">';

            if (IS_DATABASE_ROOT)
                echo '<li><img src="icon/database.png"/> <a href="database_lists.php">Danh sách database</a></li>';
            else
                echo '<li><img src="icon/database.png"/> <a href="database_tables.php">Danh sách bảng</a></li>';

            echo '</ul>';
        } else {
            goURL('database.php');
        }
    } else {
        goURL('database.php');
    }
} else {
    echo '<div class="title">' . $title . '</div>
    <div class="list">Lỗi cấu hình hoặc không kết nối được</div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="icon/database.png"/> <a href="database.php">Kết nối database</a></li>
    </ul>';
}

require 'footer.php';
