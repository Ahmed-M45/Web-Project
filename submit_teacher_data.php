<?php
header('Content-Type: application/json');

$input = file_get_contents("php://input");
$request = json_decode($input, true);

if (!$request) { exit(json_encode(["status" => "error", "message" => "Invalid Data"])); }

$conn = new mysqli("localhost", "root", "", "studentportal");

$type = $request['type'];
$teacherID = $request['teacherID'];
$courseID = $request['courseID'];
$dataList = $request['data'];

$processed = 0;

if ($type === 'attendance') {
    $date = $request['date'];
    $time = date("H:i:s");
    
    // Get Subject Name (Course Name)
    $cRow = $conn->query("SELECT CourseName FROM Course WHERE CourseID = $courseID")->fetch_assoc();
    $subjectName = $cRow['CourseName'];

    foreach ($dataList as $item) {
        $sid = $item['studentID'];
        $status = $item['status'];

        // Check existence
        $check = $conn->query("SELECT AttendanceID FROM Attendance WHERE StudentID='$sid' AND CourseID='$courseID' AND Date='$date'");
        
        if ($check->num_rows > 0) {
            // UPDATE
            $stmt = $conn->prepare("UPDATE Attendance SET Status=?, Time=?, TeacherID=? WHERE StudentID=? AND CourseID=? AND Date=?");
            $stmt->bind_param("ssssss", $status, $time, $teacherID, $sid, $courseID, $date);
        } else {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO Attendance (StudentID, TeacherID, Subject, Date, Time, Status, CourseID) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $sid, $teacherID, $subjectName, $date, $time, $status, $courseID);
        }
        if ($stmt->execute()) $processed++;
    }
    echo json_encode(["status" => "success", "message" => "Attendance saved/updated for $processed students."]);
} 

elseif ($type === 'result') {
    $subjectTitle = $request['subject'];

    foreach ($dataList as $item) {
        $sid = $item['studentID'];
        $marks = $item['marks'];
        $grade = $item['grade'];

        // Check existence
        $check = $conn->query("SELECT ResultID FROM Results WHERE StudentID='$sid' AND CourseID='$courseID' AND Subject='$subjectTitle'");

        if ($check->num_rows > 0) {
            // UPDATE
            $stmt = $conn->prepare("UPDATE Results SET Marks=?, Grade=?, TeacherID=? WHERE StudentID=? AND CourseID=? AND Subject=?");
            $stmt->bind_param("isssis", $marks, $grade, $teacherID, $sid, $courseID, $subjectTitle);
        } else {
            // INSERT
            $stmt = $conn->prepare("INSERT INTO Results (StudentID, TeacherID, Subject, Marks, Grade, CourseID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisi", $sid, $teacherID, $subjectTitle, $marks, $grade, $courseID);
        }
        if ($stmt->execute()) $processed++;
    }
    echo json_encode(["status" => "success", "message" => "Results saved/updated for $processed students."]);
}

$conn->close();
?>