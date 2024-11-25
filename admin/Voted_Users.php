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
   <title>Registered Voters</title>

   <!-- Font Awesome CDN -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<style>
  

</style>

<!-- Voter List Section -->
<section class="accounts">

   <h1 class="heading">Registered Voters</h1>

   <div class="box-container">

   <?php
      // Query to fetch voter ID and vote status
      $select_voters = $conn->prepare("SELECT ID_number, vote_status FROM voters ORDER BY voter_id ASC");
      $select_voters->execute();
      $result = $select_voters->get_result();
      
      if ($result->num_rows > 0) {
         while ($fetch_voter = $result->fetch_assoc()) {  
   ?>
   <div class="box">
      <p>ID Number: <span><?= htmlspecialchars($fetch_voter['ID_number']); ?></span></p>
      <p>Status: 
         <span style="color:<?= $fetch_voter['vote_status'] === 'voted' ? 'green' : 'red'; ?>;">
            <?= $fetch_voter['vote_status'] === 'voted' ? 'Voted' : 'Not-Voted'; ?>
         </span>
      </p>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No registered voters found.</p>';
      }
   ?>

   </div>

</section>

<!-- Custom JS -->
<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
  function updateVoterList() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_voters.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            const voters = JSON.parse(this.responseText);
            const container = document.querySelector('.box-container');
            container.innerHTML = ''; // Clear existing content

            voters.forEach(voter => {
                const box = document.createElement('div');
                box.classList.add('box');
                box.innerHTML = `
                    <p>ID Number: <span>${voter.ID_number}</span></p>
                    <p>Status: 
                        <span style="color:${voter.vote_status === 'voted' ? 'green' : 'red'};">
                            ${voter.vote_status === 'voted' ? 'Voted' : 'Not-Voted'}
                        </span>
                    </p>`;
                container.appendChild(box);
            });
        }
    };
    xhr.send();
}

// Call the function on page load and every 5 seconds
document.addEventListener('DOMContentLoaded', updateVoterList);
setInterval(updateVoterList, 5000);
 
</script>

</body>
</html>