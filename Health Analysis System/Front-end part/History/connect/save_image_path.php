<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "psy_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("fail: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

$sql = "INSERT INTO uploaded_images (image_path) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $image_path);

$image_path = $data->image_path;
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Save successful";
} else {
    echo "Save failed";
}

$stmt->close();
$conn->close();
?>
