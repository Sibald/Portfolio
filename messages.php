<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Mijn berichten</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<header>
    <?php include 'navbar.php' ?>
</header>
<body>

<?php
    function dbConnection()
    {
        $servername = "localhost";
        $username = "contact";
        $password = "Groenten98";
        $dbname = "contact";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    $currentConnection = dbConnection();
    $db_output = $currentConnection->query("SELECT * FROM messages");
    echo "<table>";
    echo "<tr class='titles'><td>Username</td><td>Email</td><td>Message</td><td>Timestamp</td></tr>";
    while($row = mysqli_fetch_array($db_output)){
        echo "<tr><td>" . $row['sender'] . "</td><td>" . $row['email'] . "</td><td>" . $row['message'] .
            "</td><td>" . $row['added_on'] . "</td></tr>";
    }
    echo "</table>";
    ?>

</body>