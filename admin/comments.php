<?php
session_start();

include '../components/connect.php';

// Check if the session or cookie contains the user_id
if (isset($_SESSION['otp_admin_id'])) {
    $admin_id = $_SESSION['otp_admin_id'];  // Get user ID from session
} elseif (isset($_COOKIE['otp_admin_id'])) {
    $admin_id = $_COOKIE['otp_admin_id'];  // Fallback to cookie if session is not set
} else {
    $admin_id = '';  // No user logged in
}

// Handle comment deletion
if (isset($_POST['delete_comment'])) {
    $delete_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_STRING);

    // Verify if the comment exists
    $verify_comment = $conn->prepare("SELECT * FROM `messages` WHERE Message_id = ?");
    $verify_comment->bind_param('i', $delete_id);
    $verify_comment->execute();
    $result = $verify_comment->get_result();

    if ($result->num_rows > 0) {
        // Delete the comment
        $delete_comment = $conn->prepare("DELETE FROM `messages` WHERE Message_id = ?");
        $delete_comment->bind_param('i', $delete_id);
        if ($delete_comment->execute()) {
            $message[] = 'Comment deleted successfully!';
        } else {
            $message[] = 'Failed to delete the comment!';
        }
    } else {
        $message[] = 'Comment already deleted or does not exist!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../images/voting-box.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Comments</title>

    <!-- Font Awesome CDN link -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="comments">

    <h1 class="heading">User Comments</h1>

    <div class="show-comments">
        <?php
        // Fetch all comments from the messages table
        $select_comments = $conn->prepare("SELECT * FROM `messages` ORDER BY Message_id DESC");
        $select_comments->execute();
        $result = $select_comments->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_comment = $result->fetch_assoc()) {
        ?>
        <div class="box">
            <div class="content">
              
                <p>Email: <?= htmlspecialchars($fetch_comment['Email_Add']); ?></p>
            </div>
            <p class="text"><?= htmlspecialchars($fetch_comment['Message']); ?></p>
            <form action="" method="post">
                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($fetch_comment['Message_id']); ?>">
                <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('Delete this comment?');">Delete Comment</button>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No comments added yet!</p>';
        }
        ?>
    </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
