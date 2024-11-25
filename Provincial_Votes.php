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
   <title>teachers</title>

   <!-- font awesome cdn link  -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->

<section class="Provincial">

   <h1 style="color: var(--black);" class="heading">Provincial Votes</h1>

   <div class="box-container">

      <div class="box offer">
         <h3>Eastern Cape Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart1"></canvas>
         </div>
         
      </div>

      <div class="box offer">
         <h3>Free State Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart2"></canvas>
         </div>
         
      </div>

      <div class="box offer">
         <h3>Gauteng Results</h3>

         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart3"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>KwaZulu-Natal Results</h3>

         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart4"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>Limpopo Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart5"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>Mpumalanga Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart6"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>Northen Cape Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart7"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>North-West Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart8"></canvas>
            </div>
         
      </div>

      <div class="box offer">
         <h3>Western Cape Results</h3>
         <div class="pie-chart">
         <canvas class="pieChart" id="provincialChart9"></canvas>
            </div>
         
      </div>

   </div>

</section>

<!-- teachers section ends -->


<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
          // Provincial Pie Chart Example
          document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart1').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart2').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart3').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart4').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart5').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart6').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart7').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });

       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart8').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });


       document.addEventListener('DOMContentLoaded', function () {
          const provincialData1 = {
          labels: ['Party A', 'Party B', 'Party C' ,'Party D'],
          datasets: [{
          data: [120, 80, 100, 50],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCD56' ,'#e74c3c'],
          borderWidth: 1
          }]
          };

         new Chart(document.getElementById('provincialChart9').getContext('2d'), {
         type: 'pie',
         data: provincialData1
        });
       });
    </script>
   
</body>
</html>