<?php
$conn = new mysqli("localhost","root","","studentportal");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM Attendance a
        JOIN Course c ON a.CourseID = c.CourseID";
$result = $conn->query($sql);

echo "<table class='table table-bordered'>
<tr>
<th>S.No</th>
<th>ID</th>
<th>Student</th>
<th>Teacher</th>
<th>Course</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>";

$serial = 1; // initialize serial number
while($r = $result->fetch_assoc()){
    echo "<tr>
    <td>".$serial++."</td>
    <td>{$r['AttendanceID']}</td>
    <td>{$r['StudentID']}</td>
    <td>{$r['TeacherID']}</td>
    <td>{$r['CourseName']}</td>
    <td>{$r['Date']}</td>
    <td>{$r['Time']}</td>
    <td>{$r['Status']}</td>
    <td>
        <button class='btn btn-sm btn-danger'
        onclick='deleteAttendance({$r['AttendanceID']})'>Delete</button>
        <!-- Uncomment below to enable edit
        <button class='btn btn-sm btn-warning' onclick='editAttendance({$r['AttendanceID']}, \"{$r['Status']}\")'>Edit</button>
        -->
    </td>
    </tr>";
}

echo "</table>";
$conn->close();
?>
