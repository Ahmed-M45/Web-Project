<?php
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'teacher') {
    exit("Not allowed");
}

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$ResultID = $_POST['ResultID'] ?? '';
$StudentID = $_POST['StudentID'];
$CourseID = $_POST['CourseID'];
$Marks = $_POST['Marks'];
$Grade = $_POST['Grade'];

if($ResultID) {
    $stmt = $conn->prepare("UPDATE Results SET StudentID=?, CourseID=?, Marks=?, Grade=? WHERE ResultID=?");
    $stmt->bind_param("ssisi", $StudentID, $CourseID, $Marks, $Grade, $ResultID);
} else {
    $stmt = $conn->prepare("INSERT INTO Results(StudentID, CourseID, Marks, Grade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $StudentID, $CourseID, $Marks, $Grade);
}

$stmt->execute();
$stmt->close();
$conn->close();
?>
