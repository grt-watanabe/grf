<?php

require_once dirname(__FILE__) . "db.class.php";
require_once dirname(__FILE__) . "debug.class.php";

$db    = new LucenDB;
$debug = new LucenDebug;
//$debug = new LucenDebugLinux;

# 初期DB接続
//$db->connect("newtouch");
$db_conn['localdb']  = $db->connect("localdb");
$db_conn['localdb2'] = $db->connect("localdb2");

//$db->select_db("test", $db_conn['localdb']);
//$db->select_db("test1", $db_conn['localdb']);
//$db->select_db("test2", $db_conn['localdb2']);


?>