<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "practiceblog";

$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sitedatasql = $conn->prepare("select * from pb_base");
$sitedatasql->execute();
$sitedatasql_result = $sitedatasql->get_result();
$sitedatasql_data = $sitedatasql_result->fetch_assoc();
?>
