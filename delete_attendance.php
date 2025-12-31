<?php
$conn = new mysqli("localhost","root","","studentportal");
$id = $_GET['id'];
$conn->query("DELETE FROM Attendance WHERE AttendanceID=$id");
$conn->close();
?>
