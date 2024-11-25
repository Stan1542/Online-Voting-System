<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">

   <section class="flex">

   <div>
        <img style="width: 5rem;" class="head-logo" src="../images/voting-box.png" alt="">
      </div>

      <a href="home.php" class="logo">Election Plateform Dashboard</a>

     
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" style="display: none;" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
            // Fetch profile using MySQLi
            $select_profile = $conn->prepare("SELECT * FROM `administrators` WHERE Admin_id  = ?");
            $select_profile->bind_param("i", $admin_id); // Use $admin_id instead of $tutor_id
            $select_profile->execute();
            $result = $select_profile->get_result();

            if ($result->num_rows > 0) {
                $fetch_profile = $result->fetch_assoc();
         ?>
         
         <h3><?= htmlspecialchars($fetch_profile['Name']); ?></h3>
         <h1 style="color: var(--black); font-size: 1.8rem;">Election Commission</h1>
         <span style="font-size: 1.8rem; color: var(--black);"><?= htmlspecialchars($fetch_profile['Position']); ?></span>
         <div class="flex-btn">
            
         </div>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
            } else {
         ?>
         <h3>please login or register</h3>
         <div class="flex-btn">
            
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
            // Fetch profile for sidebar using MySQLi
            $select_profile = $conn->prepare("SELECT * FROM `administrators` WHERE Admin_id  = ?");
            $select_profile->bind_param("i", $admin_id); // Use $admin_id instead of $tutor_id
            $select_profile->execute();
            $result = $select_profile->get_result();

            if ($result->num_rows > 0) {
                $fetch_profile = $result->fetch_assoc();
         ?>
         <h3><?= htmlspecialchars($fetch_profile['Name']); ?></h3>
         <h1 style="color: var(--black); font-size: 1.8rem;">Election Commission</h1>
         <span style="font-size: 1.8rem; color: var(--black);"><?= htmlspecialchars($fetch_profile['Position']); ?></span>
         <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
         <?php
            } else {
         ?>
      
         <?php
            }
         ?>
   </div>

   <nav class="navbar">
      <a href="dashboard.php"><i id="icon-google" class="material-symbols-outlined">home</i><span>Home</span></a>
      <a href="add_accounts.php"><i id="icon-google" class="material-symbols-outlined">group_add</i><span>Add New Staff Members</span></a>
      <a href="View_Parties_Added.php"><i id="icon-google" class="material-symbols-outlined">add_task</i><span>View Parties Added</span></a>
      <a href="comments.php"><i id="icon-google" class="material-symbols-outlined">feedback</i><span>View Voter Feedbacks</span></a>
      <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i id="icon-google" class="material-symbols-outlined">logout</i><span>logout</span></a>
   </nav>

</div>
