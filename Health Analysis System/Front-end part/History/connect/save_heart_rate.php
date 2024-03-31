<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "psy_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Fail: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

$sql = "INSERT INTO heart_rate_records (heart_rate, assessment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $heart_rate, $assessment);

$heart_rate = $data->heart_rate;
$assessment = assessHeartRate($heart_rate); 

$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Save successful";
} else {
    echo "Save failed";
}

$stmt->close();
$conn->close();

function assessHeartRate($heartRate) {
    if ($heartRate < 30) {
        return 'Extremely low heart rate. Emergency medical attention required.';
    } else if ($heartRate >= 30 && $heartRate < 40) {
        return 'Very low heart rate. Medical attention is recommended.';
    } else if ($heartRate >= 40 && $heartRate < 50) {
        return 'Low heart rate. Monitor closely and consult with a healthcare professional.';
    } else if ($heartRate >= 50 && $heartRate < 60) {
        return 'Slightly low heart rate. Monitor for changes and consult with a healthcare professional if needed.';
    } else if ($heartRate >= 60 && $heartRate <= 100) {
        return 'Normal heart rate. Maintain a healthy lifestyle.';
    } else if ($heartRate > 100 && $heartRate <= 120) {
        return 'Slightly high heart rate. Monitor for changes and consult with a healthcare professional if needed.';
    } else if ($heartRate > 120 && $heartRate <= 150) {
        return 'High heart rate. Rest and relax. Avoid strenuous activities.';
    } else if ($heartRate > 150 && $heartRate <= 200) {
        return 'Very high heart rate. Rest immediately and seek medical attention.';
    } else {
        return 'Extremely high heart rate. Emergency medical attention required.';
    }
}
?>
