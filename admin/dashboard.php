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


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="../images/voting-box.png">
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <title> Election Commission Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">

   <h1 class="heading"> Election Commission Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>welcome!</h3>
         <span  id="google-icon" class="material-symbols-outlined">
           handshake
         </span>
         <p><?= $fetch_profile['Name']; ?></p>
         <a href="profile.php" class="btn">view profile</a>
      </div>

      <div class="box add-parties">
            <h3>0</h3> <!-- Dynamically updated -->
            <div>
                <span id="google-icon" class="material-symbols-outlined">ballot</span>
            </div>
            <p>Add Parties to Ballot</p>
            <a href="add_new_parties.php" class="btn">Add New Party to Ballot</a>
        </div>

        <div class="box total-votes">
            <h3>0</h3> <!-- Dynamically updated -->
            <div>
                <span id="google-icon" class="material-symbols-outlined">new_releases</span>
            </div>
            <p>Total Votes Casted</p>
            <a href="Voted_Users.php" class="btn">View</a>
        </div>


   </div>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>
<script>
   function updateStats() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_admin_stats.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);

            // Update the "Add Parties to Ballot" count
            document.querySelector('.add-parties h3').textContent = data.total_parties;

            // Update the "Total Votes Casted" count
            document.querySelector('.total-votes h3').textContent = data.total_votes;
        }
    };
    xhr.send();
}

// Load initial stats when the page loads
document.addEventListener('DOMContentLoaded', updateStats);

// Optional: Refresh stats periodically (e.g., every 10 seconds)
setInterval(updateStats, 10000);

</script>

</body>
</html>