<?php
include '../components/connect.php';

$response = [];

// Fetch voters and their statuses
$select_voters = $conn->query("SELECT ID_number, vote_status FROM voters ORDER BY voter_id ASC");
while ($voter = $select_voters->fetch_assoc()) {
    $response[] = $voter;
}

header('Content-Type: application/json');
echo json_encode($response);
?>