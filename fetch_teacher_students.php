<?php
header('Content-Type: application/json');
$teacherID = $_GET['TeacherID'] ?? "";

if (empty($teacherID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "studentportal");

// Finds students sharing CourseIDs with the logged-in Teacher
$sql = "SELECT DISTINCT s.StudentID, s.Name 
        FROM Student s
        JOIN Studentcourse sc ON s.StudentID = sc.StudentID
        JOIN Teachercourse tc ON sc.CourseID = tc.CourseID
        WHERE tc.TeacherID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
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