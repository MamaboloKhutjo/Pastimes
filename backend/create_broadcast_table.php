<?php
// backend/create_broadcast_table.php
require_once 'DBConn.php';

$sql = "CREATE TABLE IF NOT EXISTS broadcast_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    audience VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES tbladmin(admin_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Broadcast logs table created successfully!<br>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>