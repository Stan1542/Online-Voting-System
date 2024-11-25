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

if (isset($_POST['submit'])) {

    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING); // Changed to string to handle phone number formats
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
   


    if (!is_numeric($number) || strlen($number) > 15) {
        $message[] = 'Invalid phone number!';
    } else {
        // prepared statement
        $insert_message = $conn->prepare("INSERT INTO `messages` (`voter_id`, `Name`, `Email_Add`, `Mobile_num`, `Message`) VALUES (?, ?, ?, ?, ?)");

        if ($insert_message) {
            // Bind parameters and execute
            $insert_message->bind_param("issss", $voter_id, $name, $email, $number, $msg);

            if ($insert_message->execute()) {
                $message[] = 'Message sent successfully!';
            } else {
                $message[] = 'Failed to send the message: ' . $insert_message->error;
            }

            // Close the prepared statement
            $insert_message->close();
        } else {
            $message[] = 'Failed to prepare the statement: ' . $conn->error;
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
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="icon" href="./images/voting-box.png">
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact us.png" alt="">
      </div>

      <form action="" method="post">
         <h3>get in touch</h3>
         <input type="text" placeholder="enter your name" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="enter your email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="enter your number" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="enter your message" required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="send message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>phone number</h3>
         <a href="tel:1234567890">123-456-7890</a>
         <a href="tel:1112223333">111-222-3333</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>email address</h3>
         <a href="mailto:shaikhanas@gmail.com">shaikhanas@gmail.come</a>
         <a href="mailto:anasbhai@gmail.com">anasbhai@gmail.come</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>office address</h3>
         <a href="#">flat no. 1, a-1 building, jogeshwari, mumbai, india - 400104</a>
      </div>


   </div>

</section>

<!-- contact section ends -->











<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>