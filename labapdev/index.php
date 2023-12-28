<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "hrm2";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database created <br>";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->select_db($dbname);

$sql_create_table = "CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    award_points INT NOT NULL,
    position VARCHAR(255) NOT NULL,
    employee_of_the_month INT NOT NULL,
    work_finish_points INT NOT NULL,
    collaborator_award INT NOT NULL,
    team_player INT NOT NULL,
    customer_service_award INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if ($conn->query($sql_create_table) === TRUE) {
    echo "Table created <br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Initialize $result to avoid undefined variable warning
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['insert'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $awardPoints = $_POST['award_points'];
        $position = $_POST['position'];
        $employee_of_the_month = $_POST['employee_of_the_month'];
        $work_finish_points = $_POST['work_finish_points'];
        $collaborator_award = $_POST['collaborator_award'];
        $team_player = $_POST['team_player'];
        $customer_service_award = $_POST['customer_service_award'];

        $sql_insert = "INSERT INTO employees (name, email, award_points, position, employee_of_the_month, work_finish_points, collaborator_award, team_player, customer_service_award)
            VALUES ('$name', '$email', '$awardPoints', '$position', '$employee_of_the_month', '$work_finish_points', '$collaborator_award', '$team_player', '$customer_service_award')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Record insert<br>";
        } else {
            echo "Error inserting record: " . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $awardPoints = $_POST['award_points'];
        $position = $_POST['position'];
        $employee_of_the_month = $_POST['employee_of_the_month'];
        $work_finish_points = $_POST['work_finish_points'];
        $collaborator_award = $_POST['collaborator_award'];
        $team_player = $_POST['team_player'];
        $customer_service_award = $_POST['customer_service_award'];

        $sql_update = "UPDATE employees SET name='$name', email='$email', award_points='$awardPoints', position='$position',
            employee_of_the_month='$employee_of_the_month', work_finish_points='$work_finish_points', collaborator_award='$collaborator_award',
            team_player='$team_player', customer_service_award='$customer_service_award' WHERE id=$id";
        if ($conn->query($sql_update) === TRUE) {
            echo "Record updated successfully<br>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

// Retrieve the updated result after insert/update
$sql_select = "SELECT * FROM employees";
$result = $conn->query($sql_select);
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employees Table</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="navbar">
        <a href="home.php">Views</a>
        <a href="recognition.php">Recognition</a>
        <a href="front.html">HoMe</a>
        
    </div>

    <div class="container">

        <h2>Insert into Employees table</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label>Name:</label>
            <input type="text" name="name" required><br>

            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label>Award Points:</label>
            <input type="number" name="award_points" required><br>

            <label>Position:</label>
            <input type="text" name="position" required><br>

            <label>Employee of the Month:</label>
            <input type="number" name="employee_of_the_month" required><br>

            <label>Work Finish Points:</label>
            <input type="number" name="work_finish_points" required><br>

            <label>Collaborator Award:</label>
            <input type="number" name="collaborator_award" required><br>

            <label>Team Player:</label>
            <input type="number" name="team_player" required><br>

            <label>Customer Service Award:</label>
            <input type="number" name="customer_service_award" required><br>

            <input type="submit" name="insert" value="Insert">
        </form>

        <h2>Employees table</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Award Points</th>
                <th>Position</th>
                <th>Employee of the Month</th>
                <th>Work Finish Points</th>
                <th>Collaborator Award</th>
                <th>Team Player</th>
                <th>Customer Service Award</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result !== null && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['award_points'] . "</td>";
                    echo "<td>" . $row['position'] . "</td>";
                    echo "<td>" . $row['employee_of_the_month'] . "</td>";
                    echo "<td>" . $row['work_finish_points'] . "</td>";
                    echo "<td>" . $row['collaborator_award'] . "</td>";
                    echo "<td>" . $row['team_player'] . "</td>";
                    echo "<td>" . $row['customer_service_award'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>" . $row['updated_at'] . "</td>";
                    echo "<td><a href='?edit=" . $row['id'] . "'>Edit</a></td>";
                    echo "<td><a href='?delete=" . $row['id'] . "'>DELETE</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $sql_edit = "SELECT * FROM employees WHERE id=$edit_id";
            $result_edit = $conn->query($sql_edit);

            if ($result_edit->num_rows > 0) {
                $row_edit = $result_edit->fetch_assoc();
                ?>
                <h2>Edit Record</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $row_edit['name']; ?>" required><br>

                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo $row_edit['email']; ?>" required><br>

                    <label>Award Points:</label>
                    <input type="number" name="award_points" value="<?php echo $row_edit['award_points']; ?>" required><br>

                    <label>Position:</label>
                    <input type="text" name="position" value="<?php echo $row_edit['position']; ?>" required><br>

                    <label>Employee of the Month:</label>
                    <input type="number" name="employee_of_the_month" value="<?php echo $row_edit['employee_of_the_month']; ?>" required><br>

                    <label>Work Finish Points:</label>
                    <input type="number" name="work_finish_points" value="<?php echo $row_edit['work_finish_points']; ?>" required><br>

                    <label>Collaborator Award:</label>
                    <input type="number" name="collaborator_award" value="<?php echo $row_edit['collaborator_award']; ?>" required><br>

                    <label>Team Player:</label>
                    <input type="number" name="team_player" value="<?php echo $row_edit['team_player']; ?>" required><br>

                    <label>Customer Service Award:</label>
                    <input type="number" name="customer_service_award" value="<?php echo $row_edit['customer_service_award']; ?>" required><br>

                    <input type="submit" name="update" value="Update">
                </form>
                <?php
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $sql_delete = "DELETE FROM employees WHERE id=$id";

            if ($conn->query($sql_delete) === TRUE) {
                echo "Record deleted successfully.";
            } else {
                echo "Failed to delete the record.";
            }
        }
        ?>

    </div>

</body>
</html>
