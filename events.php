<?php
// Database connection
    $servername = "gracaevidaportugal.com";
    $dbname = "u335378251_icigv";
    $dbusername = "u335378251_admin";
    $dbpassword = "Icigv@2023";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events grouped by country
$query = "SELECT country, date, event_name, responsible 
          FROM events 
          WHERE date >= CURDATE() 
          ORDER BY date ASC 
          LIMIT 6";
$result = $conn->query($query);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[$row['country']][] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($events);
?>
