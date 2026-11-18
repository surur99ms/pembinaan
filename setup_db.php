<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_pembinaan";

// 1. Connect without DB
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create DB
$sql = "CREATE DATABASE IF NOT EXISTS `$db`";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// 3. Connect with DB
$conn->select_db($db);

// 4. Import database.sql
$sql_file = file_get_contents('database.sql');
if ($conn->multi_query($sql_file)) {
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "Tables created successfully\n";
} else {
    echo "Error creating tables: " . $conn->error . "\n";
}

$conn->close();
?>
