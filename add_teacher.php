<?php
$conn = new mysqli("localhost", "root", "", "studentportal");

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_POST) {
    $id = $_POST['TeacherID'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $department = $_POST['Department'];

    $stmt = $conn->prepare("INSERT INTO Teacher(TeacherID, Name, Email, Password, Department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $id, $name, $email, $password, $department);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: AdminDashboard.cshtml");
exit();
?>
