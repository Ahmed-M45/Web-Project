<?php
header('Content-Type: application/json');
$teacherID = $_GET['TeacherID'] ?? "";

if (empty($teacherID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["error" => "Connection failed"]));

// Link Teacher to Course via Teachercourse junction table
$sql = "SELECT c.CourseID, c.CourseName 
        FROM Course c
        JOIN Teachercourse tc ON c.CourseID = tc.CourseID
        WHERE tc.TeacherID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($courses);
?>