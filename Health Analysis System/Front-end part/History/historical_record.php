<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historical Record</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Historical Record</h1>
        <div class="button-container">
            <button onclick="clearRecords()">Clear Records</button>
            <button onclick="redirectToUploadPage()">Upload Page</button>
        </div>
        <!-- Display historical records -->
        <div id="historicalRecordsContainer">
            <?php
            $mysqli = new mysqli("localhost", "root", "123456", "psy_data");

            if ($mysqli->connect_error) {
                die("fail: " . $mysqli->connect_error);
            }

            $recordsQuery = "SELECT * FROM heart_rate_records";
            $recordsResult = $mysqli->query($recordsQuery);

            if ($recordsResult->num_rows > 0) {
                while($row = $recordsResult->fetch_assoc()) {
                    echo "<div class='record'>";
                    echo "<div class='record-date'>" . $row["record_time"] . "</div>";
                    echo "<p>Heart Rate: " . $row["heart_rate"] . "</p>";
                    echo "<p>Assessment: " . $row["assessment"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No records found";
            }

            $mysqli->close();
            ?>
        </div>
    </div>

    <!-- Heart Rate Change Graph -->
    <div class="heart-rate-graph">
        <canvas id="heartRateChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Display heart rate chart
        var heartRates = <?php
            $mysqli = new mysqli("localhost", "root", "123456", "final project");

            if ($mysqli->connect_error) {
                die("fail: " . $mysqli->connect_error);
            }

            $heartRateQuery = "SELECT heart_rate FROM heart_rate_records";
            $heartRateResult = $mysqli->query($heartRateQuery);

            $heartRates = [];
            if ($heartRateResult->num_rows > 0) {
                while($row = $heartRateResult->fetch_assoc()) {
                    $heartRates[] = $row["heart_rate"];
                }
            }

            echo json_encode($heartRates);

            $mysqli->close();
        ?>;

        var ctx = document.getElementById('heartRateChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from(Array(heartRates.length), (_, i) => i + 1),
                datasets: [{
                    label: 'Heart Rate',
                    data: heartRates,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        function clearRecords() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'connect/clear_records.php');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                } else {
                    console.error('Failed to clear records.');
                }
            };
            xhr.send();
        }

        function redirectToUploadPage() {
            window.location.href = 'upload_page.php'; 
        }
    </script>
</body>
</html>
