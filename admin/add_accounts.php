<?php

session_start();

include '../components/connect.php';

// Check if the session or cookie contains the user_id
if (isset($_SESSION['otp_admin_id'])) {
    $admin_id = $_SESSION['otp_admin_id'];
} elseif (isset($_COOKIE['otp_admin_id'])) {
    $admin_id = $_COOKIE['otp_admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;
}

// Fetch the role of the logged-in admin
$stmt = $conn->prepare("SELECT Position FROM administrators WHERE Admin_id = ?");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();
$admin_role = $admin_data['Position'];

if (isset($_GET['delete']) && $admin_role === 'Manager') {
    $delete_id = $_GET['delete'];
    $delete_stmt = $conn->prepare("DELETE FROM administrators WHERE Admin_id = ?");
    $delete_stmt->bind_param('i', $delete_id);
    $delete_stmt->execute();
    header('location:admin_accounts.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins Accounts</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="icon" href="../images/voting-box.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
    <h1 class="heading">View / Add New Election Commission Staff</h1>
    <div class="box-container">

    <?php if ($admin_role === 'Manager') : ?>
    <div class="box">
        <p>Register new admin</p>
        <a href="register_admin.php" style="background-color: rgba(8, 185, 255, 0.822) ;" class="option-btn">Register</a>
    </div>
    <?php endif; ?>

    <?php
        $stmt = $conn->prepare("SELECT * FROM administrators ORDER BY Admin_id ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_accounts = $result->fetch_assoc()) {
    ?>
        <div class="box">
            <p> Staff Name : <span><?= htmlspecialchars($fetch_accounts['Name']); ?></span> </p>
            <p> Staff Number : <span><?= htmlspecialchars($fetch_accounts['Admin_Num']); ?></span> </p>
            <p> Role : <span><?= htmlspecialchars($fetch_accounts['Position']); ?></span> </p>
            <?php if ($admin_role === 'Manager' && $fetch_accounts['Admin_id'] != $admin_id) : ?>
                <a href="admin_accounts.php?delete=<?= $fetch_accounts['Admin_id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
            <?php endif; ?>
        </div>
    <?php
            }
        } else {
            echo '<p class="empty">No accounts available</p>';
        }
    ?>

    </div>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
