<?php
$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_POST) {
    $id = $_POST['StudentID'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // 1. Check if StudentID already exists
    $check = $conn->prepare("SELECT StudentID FROM Student WHERE StudentID=?");
    $check->bind_param("s", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // ID already exists
        $check->close();
        $conn->close();
        die("Student ID '$id' already exists. Please use a different ID.");
    }
    $check->close();

    // 2. Insert new student
    $stmt = $conn->prepare("INSERT INTO Student(StudentID, Name, Email, Password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $name, $email, $password);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: AdminDashboard.cshtml");
exit();
?>
