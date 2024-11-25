<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }

   

}

?>

<header class="header">

   <section class="flex">

       <div>
        <img class="head-logo" src="images/voting-box.png" alt="">
      </div>

      <a href="index.php" class="logo">Election Plateform</a>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
        <div id="search-btn" style="display: none;" class=""></div> 
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `voters` WHERE voter_id = ?");
            $select_profile ->bind_param('i', $voter_id);
            $select_profile->execute();
            $result = $select_profile->get_result();
            if ($result->num_rows > 0) {
                $fetch_profile = $result->fetch_assoc();
                $vote_status = $fetch_profile['vote_status'];

                // Set the color based on vote_status
                $status_color = ($vote_status === 'voted') ? 'green' : 'red';
                $status_text = ($vote_status === 'voted') ? 'Voted' : 'Not Voted';
                ?>
         ?>
      <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['Image']); ?>" alt="Profile Picture"> 
         <h3><?= $fetch_profile['Name']; ?></h3>
         <span style="color:<?= $status_color ?>; font-size:1.8rem;"><?= $status_text ?></span>
         <div class="flex-btn">
         </div>
         <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
            }else{
         ?>
         <h3>Please Register and Login to Vote</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <?php
      if (isset($_SESSION['otp_voter_id'])) {
          $voter_id = $_SESSION['otp_voter_id'];
          $select_profile = $conn->prepare("SELECT * FROM `voters` WHERE voter_id = ?");
          $select_profile->bind_param('i', $voter_id);
          $select_profile->execute();
          $result = $select_profile->get_result();

          if ($result->num_rows > 0) {
              $fetch_profile = $result->fetch_assoc();
              $vote_status = $fetch_profile['vote_status'];

             // Set the color based on vote_status
              $status_color = ($vote_status === 'voted') ? 'green' : 'red';
              $status_text = ($vote_status === 'voted') ? 'Voted' : 'Not-Voted';
              ?>
           <img src="uploaded_files/<?= htmlspecialchars($fetch_profile['Image']); ?>" alt="Profile Picture"> 
              <h3><?= htmlspecialchars($fetch_profile['Name']); ?></h3>
              <span style="color:<?= $status_color ?>; font-size:1.8rem;"><?= $status_text ?></span>
              <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
              <?php
          }
      } else {
          ?>
          <h3>Please Register and Login to Vote</h3>
          <div class="flex-btn">
              <a href="login.php" class="option-btn">Login</a>
              <a href="register.php" class="option-btn">Register</a>
          </div>
          <?php
      }
      ?>
   </div>

   <!-- Navigation Links -->
   <nav class="navbar">
      <a href="index.php"><i id="icon-google" class="material-symbols-outlined">home</i><span>Home</span></a>
      <a href="about.php"><i id="icon-google" class="material-symbols-outlined">question_mark</i><span>About Us</span></a>
      <a href="National_Votes.php"><i id="icon-google" class="material-symbols-outlined">monitoring</i><span>National Results</span></a>
      <a href="Cast_Vote.php"><i id="icon-google" class="material-symbols-outlined">how_to_vote</i><span>Cast Your Vote</span></a>
      <a href="contact.php"><i id="icon-google" class="material-symbols-outlined">contacts</i><span>Contact Us</span></a>
   </nav>
</div>

<!-- side bar section ends -->