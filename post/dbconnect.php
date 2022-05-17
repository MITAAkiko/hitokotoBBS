<?php
try{
    $db = new PDO('mysql:dbname=mini_bbs;host=127.0.0.1;charset=utf8','root','P@ssw0rd');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch (PDOException $e){
    echo '接続エラー:'.$e->getMessage();
}


