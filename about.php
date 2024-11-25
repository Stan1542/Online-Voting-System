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
   <title>about us</title>

   <!-- font awesome cdn link  -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="icon" href="./images/voting-box.png">
 

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/your vote counts.jpg" alt="">
      </div>

      <div class="content">
    <h3>Who Are We?</h3>
    <p>We are South Africa’s leading online voting platform, dedicated to modernizing the voting experience for all citizens. Our mission is to create a secure, seamless, and user-friendly digital space where South Africans can register, participate, and stay informed about elections. We are committed to ensuring accessibility, transparency, and efficiency in the democratic process.</p>
    <p>Through this platform, we aim to empower voters by eliminating the barriers of traditional voting methods. Whether it’s registering to vote, learning about political parties, or casting your vote from the comfort of your home, our system is designed with ease of use and reliability at its core. By embracing technology, we strive to enhance voter turnout and uphold the values of democracy.</p>
    <a href="./guide/How to use the Online Election Platform Guide.pdf" target="_blank" class="inline-btn">How To Vote</a>
     </div>

   </div>

</section>

<!-- about section ends -->

<!-- reasons section starts  -->

<section class="reviews">

   <h1 style="color: var(--black);"  class="heading">Five Reasons Why Voting is Important </h1>

   <div class="box-container">

      <div style=" text-align:center;" class="box">
         <div>
                <h3 style="font-size: 1.9rem; color: var(--black)">1. Change</h3>
         </div>
         <div>
         <p>As clichéd as it may sound, you can and should vote for change. One of the most powerful tools at our disposal to bring about change in our communities is our right to choose who to represent us and our interests. Your vote can 
         bring about better service delivery, infrastructure, jobs, education, and more if you use it to help elect better politicians!</p>
         </div>
      </div>

      <div style=" text-align:center;" class="box">
         <div>
                <h3 style="font-size: 1.9rem; color: var(--black)">2. Keeping government in check </h3>
         </div>
         <div>
         <p>
         The threat of losing voters to opposition parties is perhaps the greatest motivator for governing parties to do right by their people and deliver good governance. Your vote is your way of ensuring government competence and accountability.</p>
         </div>
      </div>

      <div style=" text-align:center;" class="box">
         <div>
                <h3 style="font-size: 1.9rem; color: var(--black)">3. Making your voice heard </h3>
         </div>
         <div>
         <p>Although your vote is only one of millions, it is still indeed that – YOUR VOTE. Your vote is your voice. Participating in elections gives you an important opportunity to shape the future of South Africa. Not voting allows others to use their voice over yours.</p>
         </div>
      </div>

      <div style=" text-align:center;" class="box">
         <div>
                <h3 style="font-size: 1.9rem; color: var(--black)">4. Democracy</h3>
         </div>
         <div>
         <p>Simply put, democracy means the will of the people. We can only realize a truly democratic society through the participation of all people in elections. The true will of the people will be determined by full participation. The government and their decisions reflect our votes.</p>
         </div>
      </div>

      <div style=" text-align:center;" class="box">
         <div>
                <h3 style="font-size: 1.9rem; color: var(--black)">5. You have a right to vote </h3>
         </div>
         <div>
         <p>An important right. A right that some of those who came before us died for! We owe it to them; we owe it to ourselves, and we owe it to one another as South Africans to exercise our constitutionally entrenched right to vote. Basically, you should vote because you can.</p>
         </div>
      </div>

   </div>

</section>

<!-- reasons section ends -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>