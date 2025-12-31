<?php
header('Content-Type: application/json');
$teacherID = $_GET['TeacherID'] ?? "";

if(empty($teacherID)){ exit(json_encode(['error'=>'No ID provided'])); }

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["error" => "DB Connection Failed"]));

$stmt = $conn->prepare("SELECT TeacherID, Name, Email, Department, Password FROM Teacher WHERE TeacherID=?");
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    echo json_encode($row);
} else {
    echo json_encode(['error'=>'Teacher not found']);
}

$stmt->close();
$conn->close();
?>