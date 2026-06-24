<?php
// Database setup script
$mysqli = new mysqli("localhost", "root", "", "");

if ($mysqli->connect_error) {
    echo "Connection failed: " . $mysqli->connect_error;
    exit;
}

$sql_file = file_get_contents(__DIR__ . '/../setup_database.sql');
$queries = array_filter(explode(';', $sql_file), function($q) {
    return trim($q) !== '';
});

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (!$mysqli->query($query)) {
            echo "Error executing query: " . $mysqli->error . "\n";
            echo "Query: " . $query . "\n\n";
        } else {
            echo "✓ Query executed\n";
        }
    }
}

echo "\n✅ Database setup completed!\n";
$mysqli->close();
?>
