<?php
function getTopEmployee($conn, $awardColumnName)
{
    $sql_select_top_employee = "SELECT id, name, $awardColumnName as award_points FROM employees ORDER BY $awardColumnName DESC LIMIT 1";
    $result_top_employee = $conn->query($sql_select_top_employee);

    if ($result_top_employee !== false && $result_top_employee->num_rows > 0) {
        return $result_top_employee->fetch_assoc();
    }

    return null;
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hrm2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define award column names (excluding 'work_finish_points')
$awardColumns = [
    'employee_of_the_month',
    'collaborator_award',
    'team_player',
    'customer_service_award'
];

// Get top employee for each award
$topEmployees = [];
foreach ($awardColumns as $awardColumn) {
    $topEmployees[$awardColumn] = getTopEmployee($conn, $awardColumn);
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Award Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="navbar">
        <a href="index.php">Employee Table</a>
        <a href="#">Recognition</a>
        <a href="award.php">Awards</a>
    </div>

    <div class="container">

        <h2>Awards</h2>

        <?php
        foreach ($awardColumns as $awardColumn) {
            echo "<div class='award-section'>";
            echo "<h3>$awardColumn Award</h3>";
            if (isset($topEmployees[$awardColumn])) {
                $employee = $topEmployees[$awardColumn];
                echo "<div class='top-employee'>";
                echo "<span class='employee-name'>" . $employee['name'] . "</span>";
                // Remove the line below to exclude award points
                // echo "<span class='award-points'>" . $employee['award_points'] . " Points</span>";
                echo "</div>";
            } else {
                echo "<p>No top employee for $awardColumn award.</p>";
            }
            echo "</div>";
        }
        ?>

    </div>

</body>
</html>

