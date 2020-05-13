<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thermo";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql_select = "SELECT id, time, td1 FROM thermo ORDER  BY id DESC";
$result_db = $conn->query($sql_select);

$data = array();

while($row = $result_db->fetch_assoc()) {
    array_push($data, $row);
}


header('Content-type: application/json');
echo json_encode($data);
$result_db->close();
$conn->close();
?>