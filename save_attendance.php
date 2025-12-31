
<?php
$conn = new mysqli("localhost","root","","studentportal");
if ($conn->connect_error) die("Connection failed");

$id = $_POST['AttendanceID'];
$status = $_POST['Status'];

$sql = "UPDATE Attendance 
        SET Status = ? 
        WHERE AttendanceID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$stmt->execute();

$conn->close();
?>
