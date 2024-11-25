<?php
session_start();

include 'components/connect.php';

// Check if the session or cookie contains the user_id
if (isset($_SESSION['otp_voter_id'])) {
  $voter_id = $_SESSION['otp_voter_id'];  // Get user ID from session
} elseif (isset($_COOKIE['otp_voter_id'])) {
  $voter_id = $_COOKIE['otp_voter_id'];  // Fallback to cookie if session is not set
} else {
  $voter_id = '';  // No user logged in
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
   <link rel="icon" href="./images/voting-box.png">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- slider for information starts here -->
<section class="hero">
  <div class=" swiper hero-slider">
    <div class="swiper-wrapper">

    <div class="swiper-slide slide">
            <div class="content">
              <span>Get Ready to Cast your Vote</span>
              <span>Make your Mark!!!</span>
            </div>

            <div class="image">
              <img src="images/cast your vote pic.jpg" alt="">
            </div>
          </div>

          <div class="swiper-slide slide">
            <div class="content">
              <span>Tired of long queues?</span>
              <span>Use the electoral system to make your mark.</span>
            </div>

            <div class="image">
              <img src="images/wait is over pic.jpg" alt="">
            </div>
          </div>

          <div class="swiper-slide slide">
            <div class="content">
              <span>Not up to date with current results</span>
              <span>No need view current results here!!</span>
            </div>

            <div class="image">
              <img src="images/view results pic.jpg" alt="">
            </div>
          </div>

    </div>
    <div class="swiper-pagination"></div>
  </div>
</section>
<!-- slider for information ends here -->

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 style="color: var(--black);" class="heading">View Election Stats</h1>

   <div class="box-container">

      <div style="grid-template-rows: 1fr 1fr 1fr; display: grid; justify-content:center; align-items:center;" class="box total-votes">
         <h3 class="title">Total Number of Votes</h3>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span id="equalizer" class="material-symbols-outlined">equalizer</span>
         </div>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span class="vote-number">Loading...</span>
         </div>
      </div>

      <div style="grid-template-rows: 1fr 1fr 1fr; display: grid; justify-content:center; align-items:center;" class="box registered-voters">
         <h3 class="title">Total Registered Voters</h3>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span id="equalizer" class="material-symbols-outlined">how_to_reg</span>
         </div>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span class="vote-number">Loading...</span>
         </div>
      </div>

      <div style="grid-template-rows: 1fr 1fr 1fr; display: grid; justify-content:center; align-items:center;" class="box total-parties">
         <h3 class="title">Total Number of Parties</h3>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span id="equalizer" class="material-symbols-outlined">ballot</span>
         </div>
         <div style="display: flex; align-items: center; justify-content: center;">
            <span class="vote-number">Loading...</span>
         </div>
      </div>

   </div>
</section>


<!-- quick select section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
 var swiper = new Swiper(".hero-slider", {
  loop: true, // Enables infinite looping
  grabCursor: true, // Makes it feel interactive
  slidesPerView: 1, // Shows one slide at a time
  spaceBetween: 10, // Optional: Add spacing between slides
  autoplay: {
    delay: 5000, // 3-second delay for autoplay
    disableOnInteraction: false, // Keep autoplay active after interaction
  },
  pagination: {
    el: ".swiper-pagination", // Pagination element
    clickable: true, // Make pagination indicators clickable
  },
  // Remove the 'flip' effect to enable a simple sliding transition
  effect: "slide", // Default Swiper effect (side-to-side sliding)
});
</script>

<script>
  function updateStats() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_stats.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);

            // Update stats in the DOM
            document.querySelector('.total-votes .vote-number').textContent = `${data.total_votes} Votes Counted`;
            document.querySelector('.registered-voters .vote-number').textContent = `${data.total_voters} Registered`;
            document.querySelector('.total-parties .vote-number').textContent = `${data.total_parties} Parties`;
        }
    };
    xhr.send();
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', updateStats);

// Optional: Refresh stats every 10 seconds
setInterval(updateStats, 10000);
</script>


   
</body>
</html>