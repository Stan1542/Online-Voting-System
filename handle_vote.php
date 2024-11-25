<?php
session_start();
include 'components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $party_id = $_POST['party_id'] ?? null;
    $voter_id = $_POST['voter_id'] ?? null;

    if (!$party_id || !$voter_id) {
        echo "Invalid request.";
        exit;
    }

    // Check if the voter has already voted
    $check_voter_status = $conn->prepare("SELECT vote_status FROM voters WHERE voter_id = ?");
    $check_voter_status->bind_param('i', $voter_id);
    $check_voter_status->execute();
    $result = $check_voter_status->get_result();

    if ($result->num_rows === 0) {
        echo "Voter not found.";
        exit;
    }

    $voter_data = $result->fetch_assoc();
    if ($voter_data['vote_status'] === 'voted') {
        echo "You have already cast your vote.";
        exit;
    }

    // Increment the party's national votes
    $update_votes = $conn->prepare("UPDATE ballot SET national_votes = national_votes + 1 WHERE id = ?");
    $update_votes->bind_param('i', $party_id);

    if ($update_votes->execute()) {
        // Update the voter's vote status
        $update_voter_status = $conn->prepare("UPDATE voters SET vote_status = 'voted' WHERE voter_id = ?");
        $update_voter_status->bind_param('i', $voter_id);
        $update_voter_status->execute();

        echo "Vote successfully cast!";
    } else {
        echo "Failed to cast your vote. Please try again.";
    }
}
?>
