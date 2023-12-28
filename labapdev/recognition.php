<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hrm2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchTerm = null;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vote'])) {
        $employeeId = $_POST['employee_id'];

        // Assuming each vote adds 1 point, you can customize this based on your requirements.
        $sql_vote = "UPDATE employees SET award_points = award_points + 1 WHERE id = $employeeId";

        if ($conn->query($sql_vote) === TRUE) {
            echo "Vote added successfully.";
        } else {
            echo "Failed to add vote: " . $conn->error;
        }
    }

    // Add votes for different recognition categories
    if (isset($_POST['employee_of_the_month_vote'])) {
        $employeeId = $_POST['employee_id'];
        $sql_vote = "UPDATE employees SET employee_of_the_month = employee_of_the_month + 1 WHERE id = $employeeId";
        if ($conn->query($sql_vote) === TRUE) {
            echo "Employee of the Month Vote added.";
        } else {
            echo "Failed to add Employee of the Month Vote: " . $conn->error;
        }
    }

    if (isset($_POST['collaborator_award_vote'])) {
        $employeeId = $_POST['employee_id'];
        $sql_vote = "UPDATE employees SET collaborator_award = collaborator_award + 1 WHERE id = $employeeId";
        if ($conn->query($sql_vote) === TRUE) {
            echo "Collaborator Award Vote added.";
        } else {
            echo "Failed to add Collaborator Award Vote: " . $conn->error;
        }
    }

    if (isset($_POST['team_player_vote'])) {
        $employeeId = $_POST['employee_id'];
        $sql_vote = "UPDATE employees SET team_player = team_player + 1 WHERE id = $employeeId";
        if ($conn->query($sql_vote) === TRUE) {
            echo "Team Player Vote added.";
        } else {
            echo "Failed to add Team Player Vote: " . $conn->error;
        }
    }

    if (isset($_POST['customer_service_vote'])) {
        $employeeId = $_POST['employee_id'];
        $sql_vote = "UPDATE employees SET customer_service_award = customer_service_award + 1 WHERE id = $employeeId";
        if ($conn->query($sql_vote) === TRUE) {
            echo "Customer Service Vote added.";
        } else {
            echo "Failed to add Customer Service Vote: " . $conn->error;
        }
    }
}

$sql_select_employees = "SELECT id, name, work_finish_points FROM employees";
if ($searchTerm !== null) {
    $sql_select_employees .= " WHERE id = '$searchTerm' OR name LIKE '%$searchTerm%'";
}
$result_employees = $conn->query($sql_select_employees);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recognition Page</title>
    <link rel="stylesheet" href="recog.css">
    <link rel="stylesheet" href="chart.css">
</head>
<body>

    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="recognition.php">Recognition</a>
        <a href="award.php">awards</a>
    </div>

    <div class="container">

        <h2>Recognition</h2>

        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <label for="search_term">Search Employee:</label>
            <input type="text" name="search" id="search_term" required placeholder="Enter ID or Name">
            <input type="submit" value="Search">
        </form>

        <?php
        if ($result_employees !== null && $result_employees->num_rows > 0) {
            while ($row_employee = $result_employees->fetch_assoc()) {
                echo "<div class='employee-card'>";
                echo "<img src='images/employee_" . $row_employee['id'] . ".jpg' alt='" . $row_employee['name'] . "' class='employee-image'>";
                echo "<div class='employee-details'>";
                echo "<span class='employee-name'>" . $row_employee['name'] . "</span>";
                echo "<span class='work-finish-points'>Work Finish Points: " . $row_employee['work_finish_points'] . "</span>";
                echo "<canvas id='workFinishChart_" . $row_employee['id'] . "' width='200' height='100'></canvas>";

                // Form for voting general award points
                echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' class='vote-form'>";
                echo "<input type='hidden' name='employee_id' value='" . $row_employee['id'] . "'>";
                echo "<input type='submit' name='vote' value='Vote' class='vote-button'>";
                echo "</form>";

                // Add buttons for each recognition category
                echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "' class='vote-form'>";
                echo "<input type='hidden' name='employee_id' value='" . $row_employee['id'] . "'>";
                echo "<input type='submit' name='employee_of_the_month_vote' value='Employee of the Month Vote' class='vote-button'>";
                echo "<input type='submit' name='collaborator_award_vote' value='Collaborator Award Vote' class='vote-button'>";
                echo "<input type='submit' name='team_player_vote' value='Team Player Vote' class='vote-button'>";
                echo "<input type='submit' name='customer_service_vote' value='Customer Service Vote' class='vote-button'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";

                // JavaScript for handling the Work Finish Points chart
                echo "<script src='https://cdn.jsdelivr.net/npm/chart.js'></script>";
                echo "<script>";
                echo "var ctxWorkFinish_" . $row_employee['id'] . " = document.getElementById('workFinishChart_" . $row_employee['id'] . "').getContext('2d');";
                echo "var myChartWorkFinish_" . $row_employee['id'] . " = new Chart(ctxWorkFinish_" . $row_employee['id'] . ", {";
                echo "type: 'bar',";
                echo "data: {";
                echo "labels: ['Work Finish'],";
                echo "datasets: [{";
                echo "label: 'Work Finish Points',";
                echo "data: [" . $row_employee['work_finish_points'] . "],";
                echo "backgroundColor: 'rgba(255, 205, 86, 0.2)',";
                echo "borderColor: 'rgba(255, 205, 86, 1)',";
                echo "borderWidth: 1";
                echo "}]";
                echo "},";
                echo "options: {";
                echo "scales: {";
                echo "y: {";
                echo "beginAtZero: true";
                echo "}";
                echo "}";
                echo "}";
                echo "});";
                echo "</script>";
            }
        } else {
            echo "No matching employees found.";
        }
        ?>

    </div>

</body>
</html>
