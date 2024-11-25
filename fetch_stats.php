<?php
include 'components/connect.php';

$response = [];

// Fetch total votes
$query_votes = $conn->query("SELECT SUM(national_votes) AS total_votes FROM ballot");
$total_votes = $query_votes->fetch_assoc()['total_votes'];
$response['total_votes'] = $total_votes ?? 0; // Default to 0 if null

// Fetch registered voters
$query_voters = $conn->query("SELECT COUNT(*) AS total_voters FROM voters");
$total_voters = $query_voters->fetch_assoc()['total_voters'];
$response['total_voters'] = $total_voters ?? 0; // Default to 0 if null

// Fetch total parties
$query_parties = $conn->query("SELECT COUNT(*) AS total_parties FROM ballot");
$total_parties = $query_parties->fetch_assoc()['total_parties'];
$response['total_parties'] = $total_parties ?? 0; // Default to 0 if null

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
