<?php
$teacherID = $_GET['TeacherID'] ?? "";
$conn = new mysqli("localhost", "root", "", "studentportal");

$sql = "SELECT r.ResultID, s.Name, r.CourseID, r.Marks, r.Grade 
        FROM Results r 
        JOIN Student s ON r.StudentID = s.StudentID 
        WHERE r.TeacherID = ?";

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
                <th>Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>";

while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['ResultID']}</td>
            <td>{$row['Name']}</td>
            <td>{$row['CourseID']}</td>
            <td>{$row['Marks']}</td>
            <td><strong>{$row['Grade']}</strong></td>
          </tr>";
}
echo "</tbody></table>";
?>