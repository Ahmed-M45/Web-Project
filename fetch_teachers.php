<?php
$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM Teacher");

echo "<table class='table table-bordered'>";
echo "<thead><tr>
        <th>S.No</th>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Actions</th>
      </tr></thead>";
echo "<tbody>";

$serial = 1; // start serial number
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$serial++."</td>"; // serial number
    echo "<td>".$row['TeacherID']."</td>";
    echo "<td>".$row['Name']."</td>";
    echo "<td>".$row['Email']."</td>";
    echo "<td>".$row['Department']."</td>";
    echo "<td>
        <a href='edit_teacher.php?id={$row['TeacherID']}' class='btn btn-warning btn-sm'>Edit</a>
        <a href='delete_teacher.php?id={$row['TeacherID']}' class='btn btn-danger btn-sm'>Delete</a>               
    </td>";
    echo "</tr>";
}
echo "</tbody></table>";

$conn->close();
?>
