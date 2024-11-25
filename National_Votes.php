<?php
session_start();

include 'components/connect.php';

// Check if the session or cookie contains the user_id
if (isset($_SESSION['otp_voter_id'])) {
    $voter_id = $_SESSION['otp_voter_id'];
} elseif (isset($_COOKIE['otp_voter_id'])) {
    $voter_id = $_COOKIE['otp_voter_id'];
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
    <title>National Votes</title>

    <!-- Font Awesome CDN Link -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="icon" href="./images/voting-box.png">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="National">
    <h1 style="color: var(--black);" class="heading">National Votes</h1>

    <div class="box-container">
        <div class="box offer">
            <h3>Party Results 2024 National Elections</h3>
            <div class="barChart">
                <canvas class="horizontal-graph" id="nationalChart"></canvas>
            </div>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('nationalChart').getContext('2d');

    const nationalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [], // Will be updated dynamically
            datasets: [{
                label: 'Votes',
                data: [], // Will be updated dynamically
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                borderWidth: 1
                

            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Votes',
                        color: '#666',
                        font: { size: 20 }
                    },
                    ticks: {
                            font: {
                                size: 14, // Font size for party names
                                family: 'Verdana, sans-serif', // Font family
                                style: 'italic', // Font style
                            },
                            color: '#333' // Text color
                        },
                        grid: {
                            color: '#ccc', // Grid line color
                            lineWidth: 2, // Grid line width
                        }
                    
                },
                y: {
                    title: {
                        display: true,
                        text: 'Parties',
                        color: '#666',
                        font: { size: 20 }
                    },
                    ticks: {
                            font: {
                                size: 14,
                                family: 'Tahoma, sans-serif',
                                weight: 'bold',
                            },
                            color: '#555'
                        },
                        grid: {
                            color: '#999', // Grid line color
                            lineWidth: 3, // Grid line width
                            borderDash: [5, 5], // Dashed grid lines
                        }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    function fetchBallotData() {
        fetch('fetch_ballot_data.php')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched Data:', data); // Debugging

                // Update chart data
                nationalChart.data.labels = data.map(row => row.party_acronym);
                nationalChart.data.datasets[0].data = data.map(row => row.national_votes);

                // Refresh the chart
                nationalChart.update();
            })
            .catch(error => console.error('Error fetching ballot data:', error));
    }

    fetchBallotData();
    setInterval(fetchBallotData, 5000); // Refresh every 5 seconds

    
</script>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');

// Function to dynamically update font sizes for mobile
function updateChartForMobile(chart) {
    const isMobile = window.matchMedia("(max-width: 768px)").matches; // Detect mobile screen size

    if (isMobile) {
        // Apply mobile-specific font styles
        chart.options.scales.x.ticks.font.size = 8; // Smaller font size for x-axis
        chart.options.scales.y.ticks.font.size = 8; // Smaller font size for y-axis
        chart.options.scales.x.ticks.font.family = 'Arial, sans-serif'; // Mobile-specific font family
        chart.options.scales.y.ticks.font.family = 'Arial, sans-serif';
    } else {
        // Revert to default or desktop styles
        chart.options.scales.x.ticks.font.size = 12;
        chart.options.scales.y.ticks.font.size = 12;
        chart.options.scales.x.ticks.font.family = 'Verdana, sans-serif';
        chart.options.scales.y.ticks.font.family = 'Tahoma, sans-serif';
    }

    chart.update(); // Apply the changes to the chart
}

const nationalChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [], // Will be updated dynamically
        datasets: [{
            label: 'Votes',
            data: [], // Will be updated dynamically
            backgroundColor: '#36A2EB',
            borderColor: '#36A2EB',
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Votes',
                    color: '#666',
                    font: { size: 12 }
                },
                ticks: {
                    font: {
                        size: 12, // Default font size
                        family: 'Verdana, sans-serif', // Default font family
                        style: 'italic', // Font style
                    },
                    color: '#333' // Text color
                },
                grid: {
                    color: '#ccc', // Grid line color
                    lineWidth: 2, // Grid line width
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Parties',
                    color: '#666',
                    font: { size: 12 }
                },
                ticks: {
                    font: {
                        size: 12, // Default font size
                        family: 'Tahoma, sans-serif', // Default font family
                        weight: 'bold',
                    },
                    color: '#555'
                },
                grid: {
                    color: '#999', // Grid line color
                    lineWidth: 3, // Grid line width
                    borderDash: [5, 5], // Dashed grid lines
                }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});

// Call the function to adjust for mobile on load
updateChartForMobile(nationalChart);

// Optional: Recheck and update if the screen is resized
window.addEventListener('resize', () => {
    updateChartForMobile(nationalChart);
});
</script>
</body>
</html>
