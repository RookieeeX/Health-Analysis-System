<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "psy_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Fail: " . $conn->connect_error);
}

$sql = "TRUNCATE TABLE uploaded_images;
        TRUNCATE TABLE heart_rate_records;";
if ($conn->multi_query($sql) === TRUE) {
    echo "Record cleared";
} else {
    echo "error: " . $conn->error;
}

$conn->close();
?>
