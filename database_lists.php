<?php

define('ACCESS', true);
define('PHPMYADMIN', true);

require '.init.php';

$title = 'Danh sách database';

require 'database_connect.php';

if (IS_CONNECT && IS_DATABASE_ROOT) {
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $title = 'Xóa database';
        $name = isset($_GET['name']) && empty($_GET['name']) == false ? addslashes($_GET['name']) : null;

        if ($name != null && isDatabaseExists($name, null, true)) {
            $title .= ': ' . $name;

            require 'header.php';

            echo '<div class="title"><div class="ellipsis">' . $title . '</div></div>';

            if (isset($_POST['accept'])) {
                if (!mysqli_query($MySQLi, "DROP DATABASE `$name`"))
                    echo '<div class="notice_failure">Xóa database thất bại: ' . mysqli_error($MySQLi) . '</div>';
                else
                    goURL('database_lists.php');
            } else if (isset($_POST['not'])) {
                goURL('database_lists.php');
            }

            echo '<div class="list">
                <form action="database_lists.php?action=delete&name=' . stripslashes($name) . '" method="post">
                    <span>Bạn có thực sự muốn xóa database không, mọi thứ trong database sẽ bị xóa hết?</span><hr/>
                    <center>
                        <input type="submit" name="accept" value="Xóa"/>
                        <input type="submit" name="not" value="Huỷ"/>
                    </center>
                </form>
            </div>';
        } else {
            require 'header.php';

            echo '<div class="title">' . $title . '</div>
            <div class="list">Tên database không tồn tại</div>';
        }

        echo '<ul class="list">
            <li><img src="icon/database.png"/> <a href="database_lists.php">Dang sách database</a></li>
        </ul>';
    } else {
        require 'header.php';

        $query = mysqli_query($MySQLi, 'SHOW DATABASES');

        if ($query) {
            echo '<div class="title">' . $title . '</div>
            <ul class="list_database">';

            while ($assoc = mysqli_fetch_assoc($query)) {
                $name = $assoc['Database'];
                $count = mysqli_query($MySQLi, 'SELECT COUNT(*) AS `c` FROM `information_schema`.`tables` WHERE `table_schema`="' . $name . '"');
                $count = mysqli_fetch_object($count);
                $count = (int) $count->c;
                
                echo '<li>
                    <p>
                        <a href="database_lists.php?action=delete&name=' . $name . '">
                            <img src="icon/database.png"/>
                        </a>
                        <a href="database_tables.php?db_name=' . $name . '">
                            <strong>' . $name . '</strong>
                        </a>
                    </p>
                    <p>
                        <span class="count_tables">' . $count . '</span>
                        <span>bảng</span>
                    </p>
                </li>';
            }

            echo '</ul>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/database.png"/> <a href="database_query.php">SQL</a></li>
                <li><img src="icon/database_create.png"/> <a href="database_create.php">Tạo database</a></li>
            </ul>';
        } else {
            echo '<div class="title">' . $title . '</div>
            <div class="list">Không thể lấy danh sách database</div>
            <div class="title">Chức năng</div>
            <ul class="list">
                <li><img src="icon/disconnect.png"/> <a href="database_disconnect.php">Ngắt kết nối database</a></li>
                <li><img src="icon/database.png"/> <a href="database_query.php">SQL</a></li>
            </ul>';
        }
    }
} else if (IS_CONNECT && !IS_DATABASE_ROOT) {
    echo '<div class="title">' . $title . '</div>
    <div class="list">Bạn đang kết nối tới một database không thể vào danh sách database</div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="icon/disconnect.png"/> <a href="database_disconnect.php">Ngắt kết nối database</a></li>
    </ul>';
} else {
    echo '<div class="title">' . $title . '</div>
    <div class="list">Lỗi cấu hình hoặc không kết nối được</div>
    <div class="title">Chức năng</div>
    <ul class="list">
        <li><img src="icon/disconnect.png"/> <a href="database_disconnect.php">Ngắt kết nối database</a></li>
    </ul>';
}

require 'footer.php';
   