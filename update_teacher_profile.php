<?php
header('Content-Type: application/json');

// Read JSON Input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) { exit(json_encode(["status" => "error", "message" => "Invalid Data"])); }

$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));

$id = $data['TeacherID'];
$name = $data['Name'];
$email = $data['Email'];
$pass = $data['Password'];

// --- FIX: SQL Query now ONLY updates Name, Email, and Password ---
// Department is NOT included here, so it will never become NULL.
$stmt = $conn->prepare("UPDATE Teacher SET Name=?, Email=?, Password=? WHERE TeacherID=?");
$stmt->bind_param("ssss", $name, $email, $pass, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>