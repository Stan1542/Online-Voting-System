<?php

session_start();

include 'components/connect.php';

// Check if the session or cookie contains the user_id
if (isset($_SESSION['otp_voter_id'])) {
    $voter_id = $_SESSION['otp_voter_id'];
} elseif (isset($_COOKIE['otp_voter_id'])) {
    $voter_id = $_COOKIE['otp_voter_id'];
} else {
    $voter_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote</title>

    <!-- Font Awesome CDN link -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="icon" href="./images/voting-box.png">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<style>

</style>

<?php include 'components/user_header.php'; ?>

<section class="National">
<h1 style="color: var(--black);" class="heading">Cast Your Vote</h1>
    <div class="box-container">
        <div class="box offer">
            <?php
            if (empty($voter_id)) {
                echo "<div style='color:red; font-size:2rem;'>Please login to cast your vote.</div>";
            } else {
                // Fetch voter details
                $select_voter = $conn->prepare("
                    SELECT vc.Province, v.vote_status, v.ID_number 
                    FROM voters_contact_info vc
                    INNER JOIN voters v ON vc.voter_id = v.voter_id
                    WHERE v.voter_id = ?");
                $select_voter->bind_param('i', $voter_id);
                $select_voter->execute();
                $result = $select_voter->get_result();

                if ($result->num_rows > 0) {
                    $voter_data = $result->fetch_assoc();
                    $province = htmlspecialchars($voter_data['Province']);
                    $vote_status = htmlspecialchars($voter_data['vote_status']);
                    $id_number = htmlspecialchars($voter_data['ID_number']);

                    echo "<h3>Voting Ballot National and Provincial Republic of <br>South Africa</h3>";
                    echo "<p class='voter-province'>Voter Province: <span style='color: var(--black);'>{$province}</span></p>";
                    echo "<p class='voter-province'>Voter Status: 
                            <span style='color:" . ($vote_status === 'voted' ? 'green' : 'red') . ";'>" . 
                            ($vote_status === 'voted' ? 'Voted' : 'Not-Voted') . "</span>
                          </p>";
                    echo "<p class='voter-province'>Voter ID Number: <span style='color: var(--black);'>{$id_number}</span></p>";

                    if ($vote_status === 'voted') {
                        echo "<div style='color:green; font-size:1.8rem;'>Your Vote Has Been Casted.</div>";
                    } else {
                        ?>
                        <!-- Ballot Table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No:</th>
                                    <th>Party Leader</th>
                                    <th>Party Name</th>
                                    <th>Party Acronym</th>
                                    <th>National Vote</th>
                                    <th>Manifesto</th>
                                    <th>Cast Vote</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $fetch_ballots = $conn->prepare("SELECT * FROM ballot ORDER BY id ASC");
                                $fetch_ballots->execute();
                                $ballots = $fetch_ballots->get_result();
                                $index = 1;

                                while ($ballot = $ballots->fetch_assoc()) {
                                    $party_id = $ballot['id'];
                                    $party_name = htmlspecialchars($ballot['party_name']);
                                    $party_acronym = htmlspecialchars($ballot['party_acronym']);
                                    $party_logo = htmlspecialchars(string: $ballot['leader_image']);
                                    ?>
                                    <tr>
                                        <td data-label="No:"><?= $index++; ?></td>
                                        <td data-label="Party Leader">
                                            <img src="uploaded_files/<?= $party_logo; ?>" alt="" class="party-pic">
                                        </td>
                                        <td data-label="Party Name"><?= $party_name; ?></td>
                                        <td data-label="Party Acronym"><?= $party_acronym; ?></td>
                                        <td data-label="National Vote">
                                        <input type="checkbox" name="vote_party" class="vote-checkbox" value="<?= $party_id; ?>" onclick="handleCheckboxSelection(this)">
                                        </td>
                                        <td data-label="Manifesto">
                                        <button class="cast-vote-btn" 
                                         id="manifesto-popup" 
                                         data-party-name="<?= addslashes(htmlspecialchars($ballot['party_name'])); ?>" 
                                          data-manifesto="<?= addslashes(htmlspecialchars($ballot['manifesto'])); ?>">
                                          Manifesto
                                         </button>
                                        </td>
                                        <td data-label="Cast Vote">
                                            <button class="cast-vote-btn" onclick="castVote(<?= $party_id; ?>)"> Vote</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }
                }
            }
            ?>
        </div>
        <div id="manifestoDiv" class="popup" style="display: none;">
    <div class="popup-content">
        <h3 id="manifestoPartyName"></h3>
        <p id="manifestoContent"></p>
        <button class="close-btn" onclick="closeManifesto()">Close</button>
    </div>
</div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
// Function to ensure only one checkbox is selected
function handleCheckboxSelection(selectedCheckbox) {
    const checkboxes = document.querySelectorAll('.vote-checkbox');

    // Uncheck all other checkboxes except the one that was clicked
    checkboxes.forEach(checkbox => {
        if (checkbox !== selectedCheckbox) {
            checkbox.checked = false;
        }
    });
}

// Function to cast the vote
function castVote(partyId) {
    const selectedCheckbox = document.querySelector('.vote-checkbox:checked');

    // Check if a checkbox is selected
    if (!selectedCheckbox || selectedCheckbox.value != partyId) {
        alert('Please check the box to cast your vote.');
        return;
    }

    // AJAX request to handle vote casting
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'handle_vote.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            alert(this.responseText);
            updateChart(); // Update the chart after vote is cast
            location.reload(); // Refresh to show updated status
        }
    };
    xhr.send('party_id=' + partyId + '&voter_id=<?= $voter_id; ?>');
}


</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Add event listener for all manifesto buttons
    const manifestoButtons = document.querySelectorAll("#manifesto-popup");
    const manifestoDiv = document.getElementById("manifestoDiv");
    const manifestoPartyName = document.getElementById("manifestoPartyName");
    const manifestoContent = document.getElementById("manifestoContent");

    manifestoButtons.forEach((button) => {
        button.addEventListener("click", () => {
            // Retrieve party name and manifesto content from data attributes
            const partyName = button.getAttribute("data-party-name");
            const manifesto = button.getAttribute("data-manifesto");

            // Populate the popup with data
            manifestoPartyName.textContent = partyName;
            manifestoContent.textContent = manifesto;

            // Show the popup
            manifestoDiv.style.display = "block";
        });
    });
});

// Close button functionality
function closeManifesto() {
    const manifestoDiv = document.getElementById("manifestoDiv");
    manifestoDiv.style.display = "none";
}

</script>

</body>
</html>
