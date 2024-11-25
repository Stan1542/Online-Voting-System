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
   <link rel="icon" href="../images/fast-food.png">
   
   <title>Placed Orders</title>

   <!-- font awesome cdn link  -->
   <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- placed orders section starts  -->


<style>
  .heading{
  font-size: 40px;
  text-align: center;
  color: #222;
  margin-bottom: 40px;
}
.table{
  width: 100%;
  border-collapse: collapse;
}
.table thead{
  background-color: rgba(8, 185, 255, 0.822);
}

.table thead tr th{
  font-size: 1.5rem;
  font-weight: 600;
  letter-spacing: 0.35px;
  color: #222;
  opacity: 1;
  padding: 12PX;
  vertical-align: top;
  border: 1px solid #dee2e685;
}
.table tbody tr td{
  font-size: 14px;
  letter-spacing: 0.35px;
  font-weight: normal;
  color: #f1f1f1;
  background-color: #3c3f44;
  padding: 8px;
  text-align: center;
  border: 1px solid #dee2e685;
}
.dim-row {
    background-color: #d3d3d3;  /* Light gray background */
    opacity: 0.6;  /* Lower the opacity to make the row appear dimmed */
    pointer-events: none;  /* Disable interactions with the row */
}
@media (max-width: 768px) {
  .table thead {
    display: none;
}
.table, .table tbody, .table tr, .table td {
    display: block;
    width: 100%;
}
.table tr {
    margin-bottom: 15px;
}
.table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
}
.table td::before {
    content: attr(data-label);
    position: absolute;
    left: 0;
    width: 50%;
    padding-left: 15px;
    font-size: 14px;
    font-weight: 600;
    text-align: left;
}
}

</style>

<!-- View Parties Section -->
<section class="placed-orders">

   <h1 style="color: var(--black)" class="heading">View Parties Added and Votes</h1>

   <table class="table">
      <thead>
         <tr>
            <th>No</th>
            <th>Leader Picture</th>
            <th>Party Name</th>
            <th>Party Acronym</th>
            <th>Party Leader</th>
            <th>Party Votes</th>
         </tr>
      </thead>
      <tbody>
        <?php
        // Fetch party data from the ballot table
        $stmt = $conn->prepare("SELECT * FROM ballot ORDER BY id ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $index = 1;

        if ($result->num_rows > 0) {
            while ($fetch_party = $result->fetch_assoc()) {
        ?>
            <tr>
                <td data-label="No"><?= $index++; ?></td>
                <td data-label="Leader Picture">
                    <img src="../uploaded_files/<?= htmlspecialchars($fetch_party['leader_image']); ?>" alt="Leader Image" style="width: 50px; height: 50px; border-radius: 50%;">
                </td>
                <td data-label="Party Name"><?= htmlspecialchars($fetch_party['party_name']); ?></td>
                <td data-label="Party Acronym"><?= htmlspecialchars($fetch_party['party_acronym']); ?></td>
                <td data-label="Party Leader"><?= htmlspecialchars($fetch_party['leader_name']); ?></td>
                <td data-label="Votes"><?= htmlspecialchars($fetch_party['national_votes']); ?></td>
            </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="6" class="empty">No parties added yet!</td></tr>';
        }

        $stmt->close();
        ?>
      </tbody>
   </table>

</section>




<!-- placed orders section ends -->
<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>




</body>
</html>