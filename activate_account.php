<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/components/connect.php";

$sql = "SELECT * FROM voters WHERE Acc_activation_hash = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    echo("<script>alert('Token not found.')</script>");
    exit();
} 

$sql = "UPDATE voters SET Acc_activation_hash = NULL WHERE voter_id = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $user["voter_id"]);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo("<script>alert('Failed to verify email.')</script>");
    exit();
}

echo("<script>alert('Email verified successfully. You can now log in.'); window.location.href = 'login.php';</script>");

?>