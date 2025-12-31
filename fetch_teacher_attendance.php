<?php
$teacherID = $_GET['TeacherID'] ?? "";
$conn = new mysqli("localhost", "root", "", "studentportal");

$sql = "SELECT a.AttendanceID, s.Name, a.CourseID, a.Date, a.Time, a.Status 
        FROM Attendance a 
        JOIN Student s ON a.StudentID = s.StudentID 
        WHERE a.TeacherID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table table-bordered table-hover mt-3'>
        <thead class='table-dark'>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['AttendanceID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['CourseID']}</td>
                <td>{$row['Date']}</td>
                <td>{$row['Time']}</td>
                <td><span class='badge " . ($row['Status'] == 'Present' ? 'bg-success' : 'bg-danger') . "'>{$row['Status']}</span></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}
echo "</tbody></table>";
?>