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

if ($admin_role !== 'Manager') {
    header('location:admin_accounts.php');
    exit;
}

if (isset($_POST['submit'])) {
    $staff_num = filter_var($_POST['staff_num'], FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $position = $_POST['position'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    if ($pass !== $cpass) {
        $message[] = 'Confirm password does not match!';
    } else {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT * FROM administrators WHERE Email = ?");
        $check_email->bind_param('s', $email);
        $check_email->execute();
        if ($check_email->get_result()->num_rows > 0) {
            $message[] = 'Email already exists!';
        } else {
            // Hash the password using password_hash()
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new admin
            $insert = $conn->prepare("INSERT INTO administrators (Admin_Num, Surname, Name, Email, Position, Password_hash) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bind_param('isssss', $staff_num, $surname, $name, $email, $position, $hashed_password);
            if ($insert->execute()) {
                $_SESSION['registration_success'] = true;
                header('location: loginOTP.php');
                exit;
            } else {
                $message[] = 'Failed to register new admin!';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="icon" href="../images/voting-box.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
    <form action="" method="POST">
        <h3>Register New Staff Member</h3>
        <?php if (!empty($message)) : ?>
            <div id="success-popup" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #f44336;
                color: white;
                padding: 15px;
                border-radius: 5px;
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
                z-index: 1000;">
                <?= implode('<br>', $message); ?>
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('success-popup').style.display = 'none';
                }, 6000);
            </script>
        <?php endif; ?>
        <input type="text" name="name" maxlength="20" required placeholder="Enter Staff Name" class="box">
        <input type="text" name="surname" maxlength="20" required placeholder="Enter Staff Surname" class="box">
        <input type="text" name="staff_num" maxlength="8" required placeholder="Enter Staff Number" class="box">
        <input type="email" name="email" required placeholder="Enter Staff Email Address" class="box">
        <select name="position" class="box" required>
            <option value="Manager">Manager</option>
            <option value="Staff Member">Staff Member</option>
        </select>
        <input type="password" name="pass" maxlength="20" required placeholder="Enter Password" class="box">
        <input type="password" name="cpass" maxlength="20" required placeholder="Confirm Password" class="box">
        <input type="submit" value="Register Now" name="submit" class="btn">
    </form>
</section>

<?php include '../components/footer.php'; ?>
<script src="../js/admin_script.js"></script>

</body>
</html>
