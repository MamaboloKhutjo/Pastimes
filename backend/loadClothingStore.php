<?php
include 'DBConn.php';

$sqlFile = 'myClothingStore.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found.");
}

$sql = file_get_contents($sqlFile);

// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // consume results
    } while ($conn->next_result());
    echo "Database restored successfully from myClothingStore.sql";
} else {
    echo "Error executing SQL: " . $conn->error;
}
$conn->close();
?>