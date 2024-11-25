<?php

session_start();
include '../components/connect.php';

if (isset($_SESSION['otp_admin_id'])) {
    $admin_id = $_SESSION['otp_admin_id'];
} elseif (isset($_COOKIE['otp_admin_id'])) {
    $admin_id = $_COOKIE['otp_admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;
}

// Fetch current admin details
$select_admin = $conn->prepare("SELECT * FROM administrators WHERE Admin_id = ?");
$select_admin->bind_param('i', $admin_id);
$select_admin->execute();
$result = $select_admin->get_result();

if ($result->num_rows > 0) {
    $fetch_admin = $result->fetch_assoc();
} else {
    echo "<script>alert('Admin not found. Please log in again.');</script>";
    header('location:login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    $update_error = '';
    $success_message = '';

    if (!empty($old_pass)) {
        if (password_verify($old_pass, $fetch_admin['Password_hash'])) {
            if ($new_pass === $confirm_pass) {
                $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

                $update_stmt = $conn->prepare("UPDATE administrators SET Name = ?, Surname = ?, Password_hash = ? WHERE Admin_id = ?");
                $update_stmt->bind_param('sssi', $name, $surname, $hashed_new_pass, $admin_id);

                if ($update_stmt->execute()) {
                    $success_message = 'Profile updated successfully!';
                } else {
                    $update_error = 'Failed to update profile. Please try again.';
                }
            } else {
                $update_error = 'New password and confirmation do not match.';
            }
        } else {
            $update_error = 'Old password is incorrect.';
        }
    } else {
        $update_stmt = $conn->prepare("UPDATE administrators SET Name = ?, Surname = ? WHERE Admin_id = ?");
        $update_stmt->bind_param('ssi', $name, $surname, $admin_id);

        if ($update_stmt->execute()) {
            $success_message = 'Name and surname updated successfully!';
        } else {
            $update_error = 'Failed to update name and surname. Please try again.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../images/fast-food.png">
    <link rel="icon" href="../images/voting-box.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>

    <!-- Font Awesome CDN link -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

    <form action="" method="POST">
        <h3>Update Profile</h3>
        <input type="text" name="name" value="<?= htmlspecialchars($fetch_admin['Name']); ?>" maxlength="30" class="box" required>
        <input type="text" name="surname" value="<?= htmlspecialchars($fetch_admin['Surname']); ?>" maxlength="30" class="box" required>
        <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box">
        <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box">
        <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box">
        <input type="submit" value="Update Now" name="submit" class="btn">

        <!-- Success/Error Messages -->
        <?php if (!empty($update_error)): ?>
            <div class="message error" id="message-box"><?= $update_error; ?></div>
        <?php elseif (!empty($success_message)): ?>
            <div style="font-size: 1.8rem; color: green; background-color: var(--black);" class="message success" id="message-box"><?= $success_message; ?></div>
        <?php endif; ?>
    </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
<script>
    // Automatically hide the message box after 3 seconds
    setTimeout(() => {
        const messageBox = document.getElementById('message-box');
        if (messageBox) {
            messageBox.style.display = 'none';
        }
    }, 3000);
</script>
</body>
</html>
