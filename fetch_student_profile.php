<?php
header('Content-Type: application/json');
$studentID = $_GET['StudentID'] ?? "";

if(empty($studentID)){ exit(json_encode(['error'=>'No ID provided'])); }

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["error" => "DB Connection Failed"]));

$stmt = $conn->prepare("SELECT StudentID, Name, Email, Password FROM Student WHERE StudentID=?");
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if($row = $result->fetch_assoc()){
    echo json_encode($row);
} else {
    echo json_encode(['error'=>'Student not found']);
}

$stmt->close();
$conn->close();
?>