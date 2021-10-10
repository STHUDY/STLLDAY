<?php
header("Content-Type: text/html;charset=utf-8");
$username = $_GET['username'];
$data = $_GET['data'];
$all_text=base64_encode($username."/".urldecode($data)."{>.}/',.;:'");
if($type == "user_name"){
    $content = base64_encode($content);
}
$con = mysqli_connect("localhost",$_COOKIE['user_sql'],$_COOKIE['password_sql'],$_COOKIE['sql_name']);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$times = getdate();
$time = $times['year'].'/'.$times['mon'].'/'.$times['mday'].'/'.$times['hours'].'/'.$times['minutes'].'/'.$times['seconds'];
$spl_all = "SELECT COUNT(*) AS 'all' FROM `user_message`";
$sqlsses = mysqli_query($con,$spl_all);
$rows = intval(mysqli_fetch_array($sqlsses)['all']) + 1;
$sqls="INSERT INTO `user_message`(`time`, `message`, `now`) VALUES ('".$time."',\"".$all_text."\",'".strval($rows)."')";
$result = mysqli_query($con,$sqls);
echo 'send_true';
?>