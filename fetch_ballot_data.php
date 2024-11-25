<?php
include 'components/connect.php';

// Fetch party names and national votes
$query = $conn->prepare("SELECT party_acronym, national_votes FROM ballot ORDER BY id ASC");
$query->execute();
$result = $query->get_result();

// Fetch and display results as JSON
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>

