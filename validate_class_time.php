<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Karachi'); // Set your timezone (e.g., 'Asia/Karachi')

$teacherID = $_GET['TeacherID'] ?? '';
$courseID = $_GET['CourseID'] ?? '';

if(empty($teacherID) || empty($courseID)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));

// Get Current Day and Time
$currentDay = date('l'); // e.g., "Monday"
$currentTime = date('H:i:s'); // e.g., "14:30:00"

// Check if a slot exists for this teacher/course right now
$sql = "SELECT * FROM Timetable 
        WHERE TeacherID = ? 
        AND CourseID = ? 
        AND DayOfWeek = ? 
        AND ? BETWEEN StartTime AND EndTime";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $teacherID, $courseID, $currentDay, $currentTime);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Valid Slot found
    $row = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success', 
        'message' => 'Class is in session.',
        'slot' => $row['StartTime'] . ' - ' . $row['EndTime']
    ]);
} else {
    // No slot found
    echo json_encode([
        'status' => 'error', 
        'message' => "You can only mark attendance during your scheduled slot."
    ]);
}

$stmt->close();
$conn->close();
?>