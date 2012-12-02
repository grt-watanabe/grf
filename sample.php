<?php
# comment...
header("Content-type: text/html; charset=UTF-8");
require_once('basic.inc.php');



# try catchを利用したエラー出力









/*
$db->connect(1);

$data = array(1,2,3,4,5,6,7,8);

$db->begine();
$db_error = true;

foreach($data as $value){
    $data_value = array( "no" => "$value");
    $result = $db->insert_array('test', $data_value);
    if (!$result) {
        $db_error = false;
        $db->rollback();
        $db->error_view(false);
    }
    $db->query_view();
}

if($db_error) $db->commit();
$db->dump_query("SELECT * FROM test");

$db->close();


$db->connect(2);

$data = array(1,2,3,4,"xxxxxxxxx",6,7,8);

$db->begine();
$db_error = true;

foreach($data as $value){
    $data_value = array( "no" => "$value");
    $result = $db->insert_array('test2', $data_value);
    if (!$result) {
        $db_error = false;
        $db->rollback();
        $db->error_view(false);
    }
    $db->query_view();
}

if($db_error) $db->commit();
$db->dump_query("SELECT * FROM test2");

$db->close();




$data = array(
   'user_name' => 'Micah Carrick',
   'email' => 'email@micahcarrick.com',
   'date_added' => '04/13/2003 4:12 PM',
   'age' => 24,
   'random_text' => "This ain't no regular text.  It's got some \"quotes\" and what not!"
   );

$data2 = "This ain't no regular text.  It's got some \"quotes\" and what not!";

$debug->view($data);
$debug->view($data2);


echo "<br>Updating the data in the table by changing the date_added... ";
$data = array('date_added' => time());
$rows = $db->update_array('users', $data, "user_id=2");
if (!$rows) $db->error_view();
if ($rows > 0) echo "$rows rows updated.";
$db->query_view();
$db->dump_query("SELECT * FROM users WHERE user_id=2");


if (!$db->execute_file('test_data.sql')) $db->error_view(false);
$db->query_view();



$data = array(
   'user_name' => 'Micah Carrick',
   'email' => 'email@micahcarrick.com',
   'date_added' => '04/13/2003 4:12 PM',
   'age' => 24,
   'random_text' => "This ain't no regular text.  It's got some \"quotes\" and what not!"
   );

$user_id = $db->insert_array('users', $data);
if (!$user_id) $db->error_view(false);
$db->query_view();
$db->dump_query("SELECT * FROM users WHERE user_id=$user_id");




echo "<br>Updating the data in the table by changing the date_added... ";
$data = array('date_added' => time());
$rows = $db->update_array('users', $data, "user_id=$user_id");
if (!$rows) $db->error_view(false);
if ($rows > 0) echo "$rows rows updated.";
$db->query_view();
$db->dump_query("SELECT * FROM users WHERE user_id=$user_id");




echo "<br>Example of how to iterate through a result set...<br> ";
$result = $db->select("SELECT user_name, email FROM users");
while ($row=$db->get_row($result)) {
   echo '<b>'.$row['user_name']."</b>'s email address is <b>".$row['email']."</b><br>";
}
*/
?>