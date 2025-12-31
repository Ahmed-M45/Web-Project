<?php
header('Content-Type: application/json');

$courseID = $_GET['CourseID'] ?? "";

if (empty($courseID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["error" => "Connection failed"]));

// Fetch students enrolled in this course via Studentcourse table
$sql = "SELECT s.StudentID, s.Name 
        FROM Student s
        JOIN Studentcourse sc ON s.StudentID = sc.StudentID
        WHERE sc.CourseID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID); // CourseID is INT
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($students);
?>