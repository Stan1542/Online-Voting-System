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
 
 $success_message = '';

if (isset($_POST['submit'])) {
    $party_name = filter_var($_POST['party_name'], FILTER_SANITIZE_STRING);
    $party_acronym = filter_var($_POST['party_acronym'], FILTER_SANITIZE_STRING);
    $leader_name = filter_var($_POST['leader_name'], FILTER_SANITIZE_STRING);
    $manifesto = filter_var($_POST['manifesto'], FILTER_SANITIZE_STRING);

    // Handle image upload
    $leader_image = $_FILES['leader_image']['name'];
    $leader_image = filter_var($leader_image, FILTER_SANITIZE_STRING);
    $image_ext = pathinfo($leader_image, PATHINFO_EXTENSION);
    $rename_image = uniqid() . '.' . $image_ext;
    $image_tmp_name = $_FILES['leader_image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename_image;

    if ($_FILES['leader_image']['size'] > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $add_party = $conn->prepare("INSERT INTO `ballot` (party_name, party_acronym, leader_name, leader_image, manifesto) VALUES (?, ?, ?, ?, ?)");
        $add_party->bind_param('ssss', $party_name, $party_acronym, $leader_name, $rename_image, $manifesto);

        if ($add_party->execute()) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_message = 'New party added successfully!';
        } else {
            $message[] = 'Failed to add party!';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="../images/voting-box.png">
   <title>Add Party</title>

   <!-- font awesome cdn link -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Display Success Pop-Up -->
<?php if (!empty($success_message)) : ?>
    <div id="success-popup" style="
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #4caf50;
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        text-align: center;
        font-size: 2rem;
        ">
        <?= $success_message; ?>
    </div>
    <script>
        // Automatically hide the popup after 6 seconds
        setTimeout(() => {
            document.getElementById('success-popup').style.display = 'none';
        }, 6000);
    </script>
<?php endif; ?>

   
<section class="video-form">

   <h1 class="heading">Add Political Party</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Party Name <span>*</span></p>
      <input type="text" name="party_name" maxlength="255" required placeholder="Enter Party Name" class="box">
      <p>Party Acronym <span>*</span></p>
      <input type="text" name="party_acronym" maxlength="6" required placeholder="Enter Party Acronym" class="box">
      <p>Party Leader Name <span>*</span></p>
      <input type="text" name="leader_name" maxlength="255" required placeholder="Enter Leader Name" class="box">
      <p>Manifesto <span>*</span></p>
      <textarea name="manifesto" class="box" required placeholder="Write Party Manifesto" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Leader Image <span>*</span></p>
      <input type="file" name="leader_image" accept="image/*" required class="box">
      <input type="submit" value="Add Party" name="submit" class="btn">
   </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>