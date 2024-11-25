<?php
include 'components/connect.php';

if (isset($_COOKIE['otp_voter_id'])) {
    $voter_id = $_COOKIE['otp_voter_id'];
} else {
    $voter_id = '';
}

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
    $id_number = filter_var($_POST['id-number'], FILTER_SANITIZE_STRING);
    $date_of_birth = $_POST['date'];
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    $race = filter_var($_POST['race'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = $_POST['cpass'];
    $mobile_num = filter_var($_POST['phone-number'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $postal_code = filter_var($_POST['postal-code'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $province = filter_var($_POST['province'], filter: FILTER_SANITIZE_STRING);

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $image;

    // Check if email already exists
    $check_existing = $conn->prepare("SELECT ID_number, Email_add FROM `voters` WHERE ID_number = ? OR Email_add = ?");
    $check_existing->bind_param('ss', $id_number, $email);
    $check_existing->execute();
    $result = $check_existing->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['ID_number'] === $id_number) {
            echo "<script>alert('ID number already registered!');</script>";
        } elseif ($row['Email_add'] === $email) {
            echo "<script>alert('Email address already registered!');</script>";
        }
    } else {
            // Insert data into `voters` table
            $insert_voter = $conn->prepare("INSERT INTO `voters` (Name, Surname, ID_number, Date_of_Birth, Gender, Race, Email_add, Password_hash, Image, vote_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Not-Voted')");
            $insert_voter->bind_param('sssssssss', $name, $surname, $id_number, $date_of_birth, $gender, $race, $email, $password, $image);
            move_uploaded_file($image_tmp_name, $image_folder);
            
            if ($insert_voter->execute()) {
                // Get the auto-generated voter_id
                $voter_id = $conn->insert_id;

                // Insert data into `voters_contact_info` table
                $insert_contact = $conn->prepare("INSERT INTO `voters_contact_info` (Email_add, Mobile_num, City, Postal_code, Address, Province, voter_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert_contact->bind_param('ssssssi', $email, $mobile_num, $city, $postal_code, $address, $province, $voter_id);

                if ($insert_contact->execute()) {
                    // Generate activation token
                    $activation_token = bin2hex(random_bytes(16));
                    $activation_token_hash = hash('sha256', $activation_token);

                    // Update the user with the activation token hash
                    $updateToken = "UPDATE `voters` SET `Acc_activation_hash` = ? WHERE `Email_add` = ?";
                    $stmt = $conn->prepare($updateToken);
                    $stmt->bind_param("ss", $activation_token_hash, $email);
                    $stmt->execute();

                    if ($stmt->affected_rows > 0) {
                        // Send activation email
                        require __DIR__ . "/Mailer.php";

                        $mail->setFrom('electionadmin@mydevhub.co.za', 'Election Platform');
                        $mail->addAddress($email);
                        $mail->Subject = "Email Verification";
                        $mail->Body = '
                        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #dddddd;">
                            <div style="padding-bottom: 10px; border-bottom: 1px solid #e6e6e6;">
                                <h1 style="font-size: 1.8rem; color: #222;">ElectionPlateform</h1>
                            </div>
                            <div style="padding: 20px 0;">
                                <h2 style="color: #333333; font-size: 24px;">Welcome to Election Plateform, ' . $name . '!</h2>
                                <p style="font-size: 16px; color: #555555;">
                                    Thank you for registering with us. We’re excited to have you on board! To complete your registration, please verify your email by clicking the link below.
                                </p>
                                <p>
                                    <a href="http://localhost/project/activate_account.php?token=' . $activation_token . '" style="font-size: 18px; color: #1a73e8; text-decoration: none;">
                                        Verify My Email
                                    </a>
                                </p>
                                <p style="font-size: 16px; color: #555555;">
                                    If you did not create this account, please disregard this email.
                                </p>
                                <p style="font-size: 16px; color: #555555;">
                                    We look forward to serving you the best experience with Election Plateform!
                                </p>
                            </div>
                            <div style="font-size: 12px; color: #999999; padding-top: 10px; border-top: 1px solid #e6e6e6;">
                                © 2024 Election Platform. All rights reserved.
                            </div>
                        </div>';

                        try {
                            $mail->send();
                            echo "<script>
                            alert('Signup successful. Please check your email inbox to verify your email.'); window.location.href = 'index.php'; </script>";
                        
                        } catch (Exception $e) {
                            echo "<script>alert('Message could not be sent. Mailer error: {$mail->ErrorInfo}')</script>";
                        }
                    } else {
                        echo "<script>alert('Error during registration.')</script>";
                    }
                } else {
                    $message[] = 'Failed to save contact info.';
                }
            } else {
                $message[] = 'Failed to register voter.';
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
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="icon" href="./images/voting-box.png">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Register To Vote</h3>
      <div class="flex">
         <div class="col">
            <p>Your FullName On ID <span>*</span></p>
            <input type="name" name="name" placeholder="Eneter Your Name" maxlength="50" required class="box">
            <p> Your Surname On ID  <span>*</span></p>
            <input type="surname" name="surname" placeholder="Enter Your Surname" maxlength="20" required class="box">
         </div>

         <div class="col">
            <p>Your ID Number <span>*</span></p>
            <input type="text" name="id-number" id="id-number" placeholder="Enter Your ID Number" maxlength="13" required class="box" oninput="validateID()">
            <p id="idError" style="color: red; display: none;">Invalid ID</p>
            <p> Your Surname On ID  <span>*</span></p>
            <input type="date" name="date" id="birthdate" placeholder="enter your surname" maxlength="20" required class="box">
         </div>

         <div class="col">
            <p>Your Email Address <span>*</span></p>
            <input type="email" name="email" id="email" placeholder="Enter Your Email Address" maxlength="50" required class="box">
            <small id="email-error" style="color: red; display: none; font-size: 1.5rem;">Invalid email address.</small>
            <p>Your Mobile Number <span>*</span></p>
            <input type="phone" name="phone-number" placeholder="Enter Your Phone Number" maxlength="13" required class="box">
         </div>

          <div class="col">
            <p>Your City<span>*</span></p>
            <input type="city" name="city" placeholder="Enter Your City" maxlength="50" required class="box">
            <p> Your Postal Code <span>*</span></p>
            <input type="Postal Code" name="postal-code" placeholder="Enter Your Postal Code" maxlength="4" required class="box">
         </div>

         <div class="col">
            <p>Your Address <span>*</span></p>
            <input type="address" name="address" placeholder="Enter Your Address" maxlength="50" required class="box">
            <p>Your Province<span>*</span></p>
            <select name="province" id="location" class="box">
             <option value="" disabled selected>Select Province</option>
             <option value= "Eastern Cape">Eastern Cape</option>
             <option value="Free State">Free State</option>
             <option value="Gauteng">Gauteng</option>
             <option value="KwaZulu-Natal">KwaZulu-Natal</option>
             <option value="Limpopo">Limpopo</option>
             <option value="Mpumalanga">Mpumalanga</option>
             <option value="Northern Cape">Northern Cape</option>
             <option value="North-West">North-West</option>
             <option value="Western Cape">Western Cape</option>
             </select>
         </div>
      </div>

      <div class="flex">
         <div class="col">
            <p>Your Gender <span>*</span></p>
            <select name="gender" id="gender" class="box">
            <option value="" disabled selected>Select Your Gender</option>
            <option value= "Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
            </select>
            <p> Your Race <span>*</span></p>
            <select name="race" id="race" class="box">
            <option value="" disabled selected>Select Your Race</option>
            <option value= "African">Black African</option>
            <option value="Coloured">Coloured</option>
            <option value="White">White</option>
            <option value="Asian/Indian">Indian/Asian</option>
        </select>
         </div>

         <div class="col">
            <p>Your Password <span>*</span></p>
            <input type="password" id="regPass" name="password" placeholder="enter your password" maxlength="20" required class="box">
            <p>Confirm Password <span>*</span></p>
            <input type="password" id="regConfPass" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
         </div>
      </div>
       
      <div class="checkB">
      <input type="checkbox" class="check" id="show-password" onclick="togglePasswordVisibility()">
      <label class="show-pass" for="show-password">Show Password</label>
      </div> 

      <div class="restictions">
       
       <div class="circle-container">
        <div class="circle" id="length-circle"></div>
        <span class="requirement-label">At least 8 characters</span>
       </div>
       <div class="circle-container">
        <div class="circle" id="uppercase-circle"></div>
        <span class="requirement-label">Uppercase letter</span>
       </div>
       <div class="circle-container">
        <div class="circle" id="lowercase-circle"></div>
        <span class="requirement-label">Lowercase letter</span>
       </div>
      <div class="circle-container">
        <div class="circle" id="number-circle"></div>
        <span class="requirement-label">Numerical character</span>
      </div>
      <div class="circle-container">
        <div class="circle" id="special-char-circle"></div>
        <span class="requirement-label">Special character</span>
       </div>
      </div>

      <p id="passwordError" style="color: red;"></p>

      <p>Select Pic <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
      <p class="link">already have an account? <a href="login.php">login now</a></p>
      <input type="submit" name="submit" value="register now" class="btn">
   </form>

</section>




<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!--JAVA SCRIPT SHOW PASSWORD STARTS-->

<script>
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById('regPass');
    const confirmPassword = document.getElementById('regConfPass')
    const showPasswordCheckbox = document.getElementById('show-password');
    
    if (showPasswordCheckbox.checked) {
        passwordInput.type = 'text';
        confirmPassword.type = 'text';
    } else {
        passwordInput.type = 'password';
        confirmPassword.type = 'password';
    }
  }
  </script>

  <script>

  </script>

<script>
    async function validateEmail() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const email = emailInput.value.trim();

        // Hide the error message initially
        emailError.style.display = 'none';

        if (email) {
            try {
                // Call the Mailcheck.ai API
                const response = await fetch(`https://api.mailcheck.ai/email/${email}?apikey=7qLl5OUv41S0XiKffnDE2OGeJiJ7UDFu`);
                const result = await response.json();

                // Debugging: Log the result
                console.log('API Response:', result);

                // Check for validity using mx and disposable fields
                if (result.mx && !result.disposable) {
                    emailError.style.display = 'none'; // Email is valid
                } else {
                    emailError.style.display = 'block';
                    emailError.textContent = 'Invalid email address. Please enter a valid one.';
                }
            } catch (error) {
                console.error('Error validating email:', error);
                emailError.style.display = 'block';
                emailError.textContent = 'Error validating email. Please try again later.';
            }
        }
    }

    // Add event listener to validate email on blur
    document.getElementById('email').addEventListener('blur', validateEmail);
</script>
   
</body>
</html>