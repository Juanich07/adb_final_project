<!-- home.php -->

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hrm2";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($dbname);

// Fetch employee names and positions
$sql_select_names_positions = "SELECT name, position FROM employees";
$result_names_positions = $conn->query($sql_select_names_positions);

// Fetch data for the award points chart
$sql_select_chart_data = "SELECT name, award_points, employee_of_the_month, work_finish_points, team_player, customer_service_award FROM employees";
$result_chart_data = $conn->query($sql_select_chart_data);

$chartLabels = [];
$chartDataPoints = [
    'award_points' => [],
    'employee_of_the_month' => [],
    'work_finish_points' => [],
    'team_player' => [],
    'customer_service_award' => [],
];

if ($result_chart_data !== null && $result_chart_data->num_rows > 0) {
    while ($row = $result_chart_data->fetch_assoc()) {
        $chartLabels[] = $row['name'];
        $chartDataPoints['award_points'][] = $row['award_points'];
        $chartDataPoints['employee_of_the_month'][] = $row['employee_of_the_month'];
        $chartDataPoints['work_finish_points'][] = $row['work_finish_points'];
        $chartDataPoints['team_player'][] = $row['team_player'];
        $chartDataPoints['customer_service_award'][] = $row['customer_service_award'];
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="chart.css">
</head>
<body>

    <div class="navbar">
        <a href="index.php">Employee Table</a>
        <a href="#">Recognition</a>
    </div>

    <div class="container">

        <h2>Employee Information</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Position</th>
            </tr>
            <?php
            if ($result_names_positions !== null && $result_names_positions->num_rows > 0) {
                while ($row = $result_names_positions->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['position'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <h2>Award Points Chart</h2>
        <canvas id="awardPointsChart" width="400" height="200"></canvas>

        <h2>Employee of the Month Chart</h2>
        <canvas id="employeeOfTheMonthChart" width="400" height="200"></canvas>

        <h2>Work Finish Chart</h2>
        <canvas id="workFinishChart" width="400" height="200"></canvas>

        <h2>Team Player Chart</h2>
        <canvas id="teamPlayerChart" width="400" height="200"></canvas>

        <h2>Customer Service Award Chart</h2>
        <canvas id="customerServiceAwardChart" width="400" height="200"></canvas>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // JavaScript for handling the award points chart
        var ctxAwardPoints = document.getElementById('awardPointsChart').getContext('2d');
        var myChartAwardPoints = new Chart(ctxAwardPoints, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Award Points',
                    data: <?php echo json_encode($chartDataPoints['award_points']); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // JavaScript for handling the employee of the month chart
        var ctxEmployeeOfTheMonth = document.getElementById('employeeOfTheMonthChart').getContext('2d');
        var myChartEmployeeOfTheMonth = new Chart(ctxEmployeeOfTheMonth, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Employee of the Month',
                    data: <?php echo json_encode($chartDataPoints['employee_of_the_month']); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // JavaScript for handling the work finish chart
        var ctxWorkFinish = document.getElementById('workFinishChart').getContext('2d');
        var myChartWorkFinish = new Chart(ctxWorkFinish, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Work Finish',
                    data: <?php echo json_encode($chartDataPoints['work_finish_points']); ?>,
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    borderColor: 'rgba(255, 205, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // JavaScript for handling the team player chart
        var ctxTeamPlayer = document.getElementById('teamPlayerChart').getContext('2d');
        var myChartTeamPlayer = new Chart(ctxTeamPlayer, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Team Player',
                    data: <?php echo json_encode($chartDataPoints['team_player']); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // JavaScript for handling the customer service award chart
        var ctxCustomerServiceAward = document.getElementById('customerServiceAwardChart').getContext('2d');
        var myChartCustomerServiceAward = new Chart(ctxCustomerServiceAward, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Customer Service Award',
                    data: <?php echo json_encode($chartDataPoints['customer_service_award']); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
