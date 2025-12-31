<?php
header('Content-Type: application/json');
$studentID = $_GET['StudentID'] ?? "";

if (empty($studentID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["error" => "Connection failed"]));

// Join Attendance with Course to get the Course Name
$sql = "SELECT a.AttendanceID, c.CourseName, a.Date, a.Time, a.Status
        FROM Attendance a
        JOIN Course c ON a.CourseID = c.CourseID
        WHERE a.StudentID = ? 
        ORDER BY a.Date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$attendance = [];
while($row = $result->fetch_assoc()) {
    $attendance[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($attendance);
?>