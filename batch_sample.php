<?php

# バッチの基本ファイル
require_once dirname(__FILE__) . "/inc/batch_config.inc.php";

#-- ここからバッチ処理 ---------------------#
$error_msg[] = get_error_time()." == Sample Batch start ===";


# Testバッチの実行
sample_batch();

$error_msg[] = get_error_time()." -- sample batch end ---";

# DB close
$db->close($db_conn['localdb1']);
$db->close($db_conn['localdb2']);

#-- エラー時のメール送信 -------------------#
$debug->view($error_msg);

$error_msg_test = "Test... msg...";
$debug->view($error_msg_test);
if( count($error_msg) ) {
    error_mail_send($error_msg, $msg_cnt);
}







function sample_batch(){
    global $db, $db_conn, $debug, $error_msg;

    try {

        # DB select
        $db->select_db("test", $db_conn['localdb1']);

        # トランザクション開始
        $db->begine();
        $error_flg = false;

        $error_flg = exe_batch($error_flg);

        # DBをコミット
        if($error_flg == false){
            $db->commit();
        }else{
            $db->rollback();
            $error_msg[] = get_error_time()." error";
        }

    } catch(exception $e) {
        echo "Error:: ".$e;
        $error_msg[] = get_error_time()." ".$e;
        $error_flg = true;
    }
    return $error_flg;
}

function exe_batch($error_flg){
    global $db, $debug, $error_msg;

    $db->dump_query("SELECT * FROM test_table");

    //$sql = "";


    return $error_flg;
}



?>