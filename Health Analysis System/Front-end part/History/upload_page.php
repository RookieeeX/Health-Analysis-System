<!DOCTYPE html>  
<html lang="en">  
<head>  
<meta charset="UTF-8">  
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>Psy Monitor</title>  
<link rel="stylesheet" href="test.css">  
<!-- 导入 Chart.js 库 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>  
<body>  

<!-- 添加返回按钮 -->
<button onclick="goBack()" class="back-button">Back</button>
  
<h1>Psy Monitor</h1>  
  
<div class="section">  
    <h2>Face scan code analysis</h2>  
    <!-- 使用自定义的按钮触发文件选择操作 -->
    <label for="fileInput" class="custom-file-input">Choose File</label>
    <input type="file" id="fileInput" accept="image/*">
    <p>Analysis result: <span id="infoDisplay"></span></p>  

    <!-- 新增图像展示区域 -->
    <div id="imageDisplay"></div>
</div>  
  
<div class="section">  
    <h2>Heart rate analysis</h2>  
    <label for="heartRateInput">Heart rate:</label>  
    <input type="number" id="heartRateInput" placeholder="submit Heart rate">  
    <button onclick="checkHeartRate()">Check heart rate</button>  

    <!-- 新增按钮清空当前的心率数值 -->
    <button onclick="clearHeartRates()">Clear Heart Rates</button>

    <!-- 新增按钮计算平均心率 -->
    <button onclick="calculateAverage()">Calculate Average</button>

    <!-- 新增按钮导出图表 -->
    <button onclick="exportChart()">Export Chart</button>

    <!-- 新增跳转按钮 -->
    <button onclick="redirectToHistory()" class="redirect-button">View Historical Records</button>
    
    <p>Output: <span id="resultDisplay"></span></p>  

    <!-- 新增结果可视化展示 -->
    <div id="resultVisualization"></div>
</div>

<script>
// 返回按钮的功能
function goBack() {
    window.history.back();
}

// 修改函数以处理上传的文件
document.getElementById('fileInput').addEventListener('change', function(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function() {
            var imageData = reader.result;
            var img = new Image();
            img.src = imageData;
            img.onload = function() {
                var MAX_WIDTH = 800;
                var MAX_HEIGHT = 600;
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var width = img.width;
                var height = img.height;
                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);
                var resizedImageData = canvas.toDataURL('image/jpeg');
                document.getElementById('infoDisplay').innerText = 'Uploaded photo is being processed...';

                // 显示上传的图像
                var resizedImg = document.createElement('img');
                resizedImg.src = resizedImageData;
                resizedImg.style.maxWidth = '100%';
                document.getElementById('imageDisplay').innerHTML = '';
                document.getElementById('imageDisplay').appendChild(resizedImg);

                // 将上传的图像保存到本地存储
                localStorage.setItem('uploadedImageData', resizedImageData);

                // 保存图片路径到数据库
                saveImagePathToDatabase(resizedImageData);
            };
        };
    }
});

// 保存图片路径到数据库
function saveImagePathToDatabase(imageData) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'connect/save_image_path.php'); // 
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Image path saved successfully.');
        } else {
            console.error('Failed to save image path.');
        }
    };
    xhr.send(JSON.stringify({ image_path: imageData }));
}

function checkHeartRate() {  
    var heartRate = parseInt(document.getElementById('heartRateInput').value, 10);  
    if (isNaN(heartRate)) { 
        document.getElementById('resultDisplay').innerText = 'submit heart rate';  
        return;  
    }  
    
    // 保存心率数据到本地存储
    var savedHeartRates = JSON.parse(localStorage.getItem('heartRates')) || [];
    savedHeartRates.push(heartRate);
    localStorage.setItem('heartRates', JSON.stringify(savedHeartRates));

    // 清空结果可视化展示区域
    document.getElementById('resultVisualization').innerHTML = '';

    // 显示当前心率数据
    document.getElementById('resultDisplay').innerText = 'Current heart rate: ' + heartRate;

    // 对当前心率进行医学评价并显示
    var assessment = assessHeartRate(heartRate);
    document.getElementById('resultDisplay').innerText += '\n' + assessment;

    // 结果可视化展示
    for (var i = 0; i < savedHeartRates.length; i++) {
        addHeartRateToVisualization(savedHeartRates[i]);
    }

    // 将心率保存到本地存储
    localStorage.setItem('uploadedHeartRate', heartRate);

    // 保存心率数据到数据库
    saveHeartRateToDatabase(heartRate);
}

// 保存心率数据到数据库
function saveHeartRateToDatabase(heartRate) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'connect/save_heart_rate.php'); // 
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Heart rate saved successfully.');
        } else {
            console.error('Failed to save heart rate.');
        }
    };
    xhr.send(JSON.stringify({ heart_rate: heartRate }));
}

// 医学评价函数
function assessHeartRate(heartRate) {
    if (heartRate < 30) {
        return 'Extremely low heart rate. Emergency medical attention required.';
    } else if (heartRate >= 30 && heartRate < 40) {
        return 'Very low heart rate. Medical attention is recommended.';
    } else if (heartRate >= 40 && heartRate < 50) {
        return 'Low heart rate. Monitor closely and consult with a healthcare professional.';
    } else if (heartRate >= 50 && heartRate < 60) {
        return 'Slightly low heart rate. Monitor for changes and consult with a healthcare professional if needed.';
    } else if (heartRate >= 60 && heartRate <= 100) {
        return 'Normal heart rate. Maintain a healthy lifestyle.';
    } else if (heartRate > 100 && heartRate <= 120) {
        return 'Slightly high heart rate. Monitor for changes and consult with a healthcare professional if needed.';
    } else if (heartRate > 120 && heartRate <= 150) {
        return 'High heart rate. Rest and relax. Avoid strenuous activities.';
    } else if (heartRate > 150 && heartRate <= 200) {
        return 'Very high heart rate. Rest immediately and seek medical attention.';
    } else {
        return 'Extremely high heart rate. Emergency medical attention required.';
    }
}

// 添加心率数据到可视化展示区域
function addHeartRateToVisualization(heartRate) {
    var savedHeartRates = JSON.parse(localStorage.getItem('heartRates')) || [];

    // 获取已有的图表对象
    var resultChartCanvas = document.querySelector('.resultChartCanvas');
    if (!resultChartCanvas) {
        // 如果没有图表，创建新的
        resultChartCanvas = document.createElement('canvas');
        resultChartCanvas.className = 'resultChartCanvas';
        document.getElementById('resultVisualization').appendChild(resultChartCanvas);
    }

    // 更新现有图表数据
    var ctx = resultChartCanvas.getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: savedHeartRates.map(function(value, index) { return index + 1; }),
            datasets: [{
                label: 'Heart Rate',
                data: savedHeartRates,
                borderColor: 'rgba(54, 162, 235, 1)',
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
}

// 清空当前的心率数值
function clearHeartRates() {
    localStorage.removeItem('heartRates');
    document.getElementById('resultVisualization').innerHTML = '';
    document.getElementById('resultDisplay').innerText = 'Heart rates cleared';
}

// 计算平均心率
function calculateAverage() {
    var savedHeartRates = JSON.parse(localStorage.getItem('heartRates')) || [];
    var sum = savedHeartRates.reduce(function(acc, val) {
        return acc + val;
    }, 0);
    var average = sum / savedHeartRates.length;

    // 显示平均心率
    document.getElementById('resultDisplay').innerText = 'Average heart rate: ' + average.toFixed(2);
}

// 导出图表
function exportChart() {
    // 获取图表的 base64 编码数据
    var resultChartCanvas = document.querySelector('.resultChartCanvas');
    var chartDataURL = resultChartCanvas.toDataURL('image/png');

    // 创建一个隐藏的链接
    var downloadLink = document.createElement('a');
    downloadLink.href = chartDataURL;
    downloadLink.download = 'heart_rate_chart.png';

    // 点击链接进行下载
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// 跳转到历史记录页面
function redirectToHistory() {
    window.location.href = 'historical_record.php';
}

</script>  

</body>  
</html>
