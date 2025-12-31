<?php
$conn = new mysqli("localhost","root","","studentportal");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$sql = "SELECT r.ResultID, s.Name AS Student, t.Name AS Teacher, c.CourseName, r.Marks, r.Grade
        FROM Results r
        JOIN Student s ON r.StudentID = s.StudentID
        JOIN Teacher t ON r.TeacherID = t.TeacherID
        JOIN Course c ON r.CourseID = c.CourseID";
$result = $conn->query($sql);

echo "<table class='table table-bordered'>
<tr>
<th>S.No</th>
<th>Result ID</th>
<th>Student</th>
<th>Teacher</th>
<th>Course</th>
<th>Marks</th>
<th>Grade</th>
</tr>";

$serial = 1; // initialize serial number
while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>".$serial++."</td>
            <td>{$row['ResultID']}</td>
            <td>{$row['Student']}</td>
            <td>{$row['Teacher']}</td>
            <td>{$row['CourseName']}</td>
            <td>{$row['Marks']}</td>
            <td>{$row['Grade']}</td>
          </tr>";
}

echo "</table>";
$conn->close();
?>
