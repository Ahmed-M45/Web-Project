<?php
$conn = new mysqli("localhost", "root", "", "studentportal");
if ($conn->connect_error) {
    die("Connection failed");
}

$name  = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$query = $_POST['query'];

$sql = "INSERT INTO Queries (Name, Email, PhoneNumber, Query)
        VALUES ('$name', '$email', '$phone', '$query')";

if ($conn->query($sql)) {
    echo "<script>
            alert('Your query has been submitted successfully');
            window.location.href='ContactUs.html';
          </script>";
} else {
    echo "Error";
}

$conn->close();
?>
