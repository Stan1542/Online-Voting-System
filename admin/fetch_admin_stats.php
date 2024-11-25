<?php
include '../components/connect.php';

$response = [];

// Fetch total parties
$query_parties = $conn->query("SELECT COUNT(*) AS total_parties FROM ballot");
$total_parties = $query_parties->fetch_assoc()['total_parties'];
$response['total_parties'] = $total_parties ?? 0;

// Fetch total votes cast
$query_votes = $conn->query("SELECT SUM(national_votes) AS total_votes FROM ballot");
$total_votes = $query_votes->fetch_assoc()['total_votes'];
$response['total_votes'] = $total_votes ?? 0;

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
