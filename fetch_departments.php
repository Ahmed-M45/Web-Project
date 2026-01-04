<?php
// connect to database
include 'db.php'; 

header('Content-Type: application/json');

$sql = "SELECT DeptID, DeptName FROM Department";
$result = $conn->query($sql);

$departments = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

echo json_encode($departments);
?>