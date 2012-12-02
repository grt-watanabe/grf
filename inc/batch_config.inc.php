<?php
header("Content-type: text/html; charset=UTF-8");

## Config #######################################################################

# batch name
$batch_name = array(
        "name"        => "test_batch",
        "description" => "Testバッチ"
);

# プログラムパス
$path = "C:/xampp/htdocs/lucen";

# Mail送信＆Log出力フラグ
$mail_flg = false;
$log_flg  = true;

# メールアドレス情報
define("BATCH_ERROR_MAIL",  "email@example.com");
define("MAIL_FROM_ADDRESS", "from@mail.com");

# ログフォルダ
$log_path = $path."/log/";
$msg_cnt = 2; //エラーじゃないログ数

# 使用クラス
require_once($path.'/inc/db.class.php');
require_once($path.'/inc/debug.class.php');

$db    = new grtDB;
$debug = new grtDebug;
//$debug = new grtDebugLinux;

# 日付
$today = date("ymd");

# 初期DB接続
//$db->connect("newtouch");
$db_conn['localdb1'] = $db->connect("localdb1");
$db_conn['localdb2'] = $db->connect("localdb2");

//$db->select_db("test", $db_conn['localdb1']);
//$db->select_db("test1", $db_conn['localdb1']);
//$db->select_db("test2", $db_conn['localdb2']);

## Function ####################################################################

function get_error_log_file_name(){
    global $log_path, $batch_name, $today;

    $error_log_file_name = $log_path.$batch_name['name']."_error_".$today.".log";
    //echo "#---------------- ".$error_log_file_name;
    return $error_log_file_name;
}

# エラーメール送信用
function error_mail_send($get_error_msg, $msg_cnt){
    global $path, $batch_name, $log_flg;

    $to_mail   = BATCH_ERROR_MAIL;
    $from_mail = MAIL_FROM_ADDRESS;

    # メールのテンプレート
    $ini_file = $path."/inc/mail.template.ini";
    //echo_view($ini_file); exit;
    $template = parse_ini_file($ini_file);

    $subject = str_replace("%BATCH_NAME%", $batch_name['name'], $template['MAIL_SUBJECT']);
    array_shift($template);

    //array_view($template);
    //echo_view($subject); exit;

    foreach($get_error_msg as $msg_key => $msg_value){
        $msg_value = str_replace("`","",$msg_value);
        $msg_value = str_replace("'","",$msg_value);
        $error_msg[$msg_key] = $msg_value;
    }

    $error_msg_value = "";
    if( is_array($error_msg) ){
        foreach($error_msg as $value){
            $error_msg_value .= "\n".$value;
        }
    }
    $error_log_file = get_error_log_file_name();
    $mail_value_arr = array(
            'BATCH_DESCRIPTION' => $batch_name['description'],
            'LOG_NAME'          => $error_log_file,
            'DATETIME'          => date('Y-m-d H:i:s'),
            'SEND_MESSAGE'      => $error_msg_value
    );
    $mail_body = make_mail_body($template, $mail_value_arr);

    # mail send
    if(count($get_error_msg) > $msg_cnt) Lucen_mail_send($to_mail, $from_mail, $subject, $mail_body);

    # log write
    if($log_flg) write_error_log($error_msg);
    return;
}

# メール送信
function Lucen_mail_send($to_mail, $from_mail, $subject, $message, $cc_mail=NULL, $bcc_mail=NULL){
    global $mail_flg;

    # メールのヘッダーを設定
    $headers = "From: {$from_mail}\n";
    if($cc_mail) $headers .= "Cc: {$cc_mail}\n";
    if($bcc_mail) $headers .= "Bcc: {$bcc_mail}\n";
    $headers .= "Return-Path: {$from_mail}\n";
    $headers .= "Reply-To: {$from_mail}\n";
    $headers .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
    $headers .= "Content-Transfer-Encoding: 7bit";

    # メールのタイトルと内容の文字コードを変換
    $subject = stripslashes(stripslashes($subject));
    $subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"jis","utf-8"))."?=";
    $message = mb_convert_encoding($message,"jis","utf-8");

    # メール送信
    if($mail_flg) $result = mail($to_mail, $subject, $message, $headers);
    else $result = true;

    return $result;
}

# メールBody作成
function make_mail_body($template, $mail_value_arr){
    $mail_body = "";
    foreach($template as $key => $value){
        $mail_body .= str_replace('%'.$key.'%', $mail_value_arr[$key], $value)."\n";
    }
    return $mail_body;
}

# ログファイル作成
function write_error_log($error_msg){
    global $error_log_file;

    //echo_view($error_log_file);
    # ログの書き込み
    if( count($error_msg) ){
        foreach($error_msg as $msg_value){
            $command = 'echo "'.$msg_value.'" >> '.$error_log_file;
            exec($command);
            //echo_view($command);
        }
        unset($error_msg);
    }
    return;
}

# エラー発生時間
function get_error_time(){
    $datetime = "\n".date('Y-m-d H:i:s');
    return $datetime;
}

#-- Debug
function echo_view($value){
    echo "\n|debug:: ".$value."\n";
    return;
}

function array_view($value){
    echo "\n|debug:: ";
    print_r($value);
    echo "\n";
    return;
}

?>

