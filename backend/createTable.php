<?php
include 'DBConn.php';

// Drop table if exists
$sql = "DROP TABLE IF EXISTS tblUser";
if ($conn->query($sql) === TRUE) {
    echo "Table tblUser dropped successfully.<br>";
}

// Recreate tblUser
$createTableSQL = "
CREATE TABLE tblUser (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('buyer','seller') NOT NULL,
    city VARCHAR(100),
    bio TEXT,
    profile_image VARCHAR(255),
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTableSQL) === TRUE) {
    echo "Table tblUser created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Load data from userData.txt
$file = 'userData.txt';
if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $stmt = $conn->prepare("INSERT INTO tblUser (first_name, last_name, email, username, password_hash, role, city, bio, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if (count($data) == 9) {
            $stmt->bind_param("ssssssssi", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]);
            $stmt->execute();
        }
    }
    $stmt->close();
    echo "Data loaded from userData.txt successfully.<br>";
} else {
    echo "userData.txt not found.<br>";
}

$conn->close();
?>