<?php
header("Content-Type: text/html;charset=utf-8");
$number = $_GET['number'];
$con = mysqli_connect("localhost",$_COOKIE['user_sql'],$_COOKIE['password_sql'],$_COOKIE['sql_name']);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$sqls="SELECT * FROM `user_message` WHERE `now` = ".$number;
$sqlss = mysqli_query($con,$sqls);
$row = mysqli_fetch_array($sqlss);
echo base64_decode($row['message']).$row['time'];
?>