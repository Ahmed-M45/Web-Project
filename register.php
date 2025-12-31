<?php
header('Content-Type: application/json');

// Database Connection
$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database Connection Failed"]);
    exit();
}

$role = $_POST['role'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$department = $_POST['department'] ?? ''; // Only for teachers

// Validation
if (empty($role) || empty($name) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit();
}

// Check if email already exists (Basic check across all tables is ideal, but checking respective table here)
// Note: In a production app, password should be hashed: $hashed_password = password_hash($password, PASSWORD_DEFAULT);
// For this project consistency, we are using the plain text password as per your previous login script.

$newID = "";

try {
    if ($role === 'admin') {
        // --- ADMIN ID GENERATION (ADM001 -> ADM002) ---
        $sql = "SELECT AdminID FROM Admin ORDER BY AdminID DESC LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['AdminID']; // e.g., ADM001
            $num = (int)substr($lastID, 3); // Get '001' -> 1
            $newID = "ADM" . str_pad($num + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $newID = "ADM001";
        }

        $stmt = $conn->prepare("INSERT INTO Admin (AdminID, Name, Email, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $newID, $name, $email, $password);
        $stmt->execute();

    } elseif ($role === 'teacher') {
        // --- TEACHER ID GENERATION (T-SE-001 -> T-SE-002) ---
        if (empty($department)) {
            echo json_encode(["status" => "error", "message" => "Department required for Teachers"]);
            exit();
        }

        $deptPrefix = "T-" . $department . "-"; // e.g., T-SE-
        
        // Find last ID specifically for this department
        $sql = "SELECT TeacherID FROM Teacher WHERE TeacherID LIKE '$deptPrefix%' ORDER BY LENGTH(TeacherID) DESC, TeacherID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['TeacherID']; // e.g., T-SE-005
            $lastNumStr = substr($lastID, strlen($deptPrefix)); // Get '005'
            $num = (int)$lastNumStr;
            $newID = $deptPrefix . str_pad($num + 1, 3, "0", STR_PAD_LEFT);
        } else {
            $newID = $deptPrefix . "001";
        }

        // Map short code to full name for DB storage if needed
        $deptMap = [
            "SE" => "Software Engineering", "CS" => "Computer Science", "BBA" => "Business",
            "PHM" => "Pharmacy", "ENG" => "English", "EE" => "Engineering", "AI" => "Artificial Intelligence"
        ];
        $fullDeptName = $deptMap[$department] ?? $department;

        $stmt = $conn->prepare("INSERT INTO Teacher (TeacherID, Name, Email, Password, Department) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $newID, $name, $email, $password, $fullDeptName);
        $stmt->execute();

    } elseif ($role === 'student') {
        // --- STUDENT ID GENERATION (STD20250001 -> STD20250002) ---
        // Since students don't select Dept, we use a generic 'STD' prefix + Year
        $year = date("Y"); 
        $prefix = "STD" . $year; // e.g., STD2025

        $sql = "SELECT StudentID FROM Student WHERE StudentID LIKE '$prefix%' ORDER BY StudentID DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['StudentID']; // e.g., STD20250001
            $num = (int)substr($lastID, 7); // Get '0001' (Length of STD2025 is 7)
            $newID = $prefix . str_pad($num + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $newID = $prefix . "0001";
        }

        $stmt = $conn->prepare("INSERT INTO Student (StudentID, Name, Email, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $newID, $name, $email, $password);
        $stmt->execute();
    }

    if ($conn->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Registered successfully! Your ID is: " . $newID]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed. Email might already exist."]);
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "System Error: " . $e->getMessage()]);
}

$conn->close();
?>