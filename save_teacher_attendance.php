<?php
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'teacher') {
    exit("Not allowed");
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$AttendanceID = $_POST['AttendanceID'] ?? '';
$StudentID = $_POST['StudentID'];
$CourseID = $_POST['CourseID'];
$Date = $_POST['Date'];
$Time = $_POST['Time'];
$Status = $_POST['Status'];

if($AttendanceID) {
    $stmt = $conn->prepare("UPDATE Attendance SET StudentID=?, CourseID=?, Date=?, Time=?, Status=? WHERE AttendanceID=?");
    $stmt->bind_param("sssssi", $StudentID, $CourseID, $Date, $Time, $Status, $AttendanceID);
} else {
    $stmt = $conn->prepare("INSERT INTO Attendance(StudentID, CourseID, Date, Time, Status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $StudentID, $CourseID, $Date, $Time, $Status);
}

$stmt->execute();
$stmt->close();
$conn->close();
?>
