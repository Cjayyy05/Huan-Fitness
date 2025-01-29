<?php
// Include database connection
include('db_conn.php');

session_start(); 

// Retrieve user_id from session, if available
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$is_member = false;

if ($user_id) {
    // Check if there's an associated member_id for this user in huan_fitness_users
    $member_query = "SELECT member_id FROM huan_fitness_users WHERE user_id = ?";
    $stmt = $conn->prepare($member_query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    // If a member_id exists, the user may be a member or expired member
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($member_id);
        $stmt->fetch();
        $stmt->close(); 

        if (!empty($member_id)) {
            // Check the status of the member
            $status_query = "SELECT status FROM huan_fitness_members WHERE member_id = ?";
            $status_stmt = $conn->prepare($status_query);
            if (!$status_stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $status_stmt->bind_param("s", $member_id); 
            $status_stmt->execute();
            $status_stmt->store_result();
            
            if ($status_stmt->num_rows > 0) {
                $status_stmt->bind_result($status);
                $status_stmt->fetch();
                
                // Determine if the member is active or expired
                if ($status === 'inactive') {
                    $is_member = false;
                } else if ($status === 'expired') {
                    $is_expired_member = true;
                } else {
                    $is_member = true; // Active member
                }
            }
            $status_stmt->close(); 
        }
    }
}

// Fetch the latest 5 weight records for the user
$weight_query = "SELECT weight, date FROM weights WHERE user_id = ? ORDER BY date DESC LIMIT 5";
$weight_stmt = $conn->prepare($weight_query);
$weight_stmt->bind_param("i", $user_id);
$weight_stmt->execute();
$weight_result = $weight_stmt->get_result();

// Initialize arrays to hold weight data
$weight_values = [];
$weight_dates = [];

// Fetch data into arrays
while ($row = $weight_result->fetch_assoc()) {
    $weight_values[] = $row['weight'];
    $weight_dates[] = $row['date'];
}

// Fetch the last 5 water intake records for the user
$water_query = "SELECT amount, date FROM water_consumption WHERE user_id = ? ORDER BY date DESC LIMIT 5";
$water_stmt = $conn->prepare($water_query);
$water_stmt->bind_param("i", $user_id);
$water_stmt->execute();
$water_result = $water_stmt->get_result();

// Initialize arrays to hold water intake data
$water_intake_values = [];
$water_dates = [];

// Fetch data into arrays
while ($row = $water_result->fetch_assoc()) {
    $water_intake_values[] = $row['amount'];
    $water_dates[] = $row['date'];
}

// Fetch today's exercise records
$current_date = date('Y-m-d'); // Get the current date in Y-m-d format
$exercise_query = "SELECT name, date FROM exercises WHERE user_id = ? AND DATE(date) = ?";
$exercise_stmt = $conn->prepare($exercise_query);
$exercise_stmt->bind_param("is", $user_id, $current_date);
$exercise_stmt->execute();
$exercise_result = $exercise_stmt->get_result();

// Initialize array to hold today's exercise records
$today_exercises = [];

// Fetch data into array
while ($row = $exercise_result->fetch_assoc()) {
    $today_exercises[] = $row; // Store the whole row (name and date)
}

// Fetch today's total water consumption
$total_water_query = "SELECT SUM(amount) AS total_consumed FROM water_consumption WHERE user_id = ? AND DATE(date) = ?";
$total_water_stmt = $conn->prepare($total_water_query);
$total_water_stmt->bind_param("is", $user_id, $current_date);
$total_water_stmt->execute();
$total_water_result = $total_water_stmt->get_result();

if ($total_water_result) {
    $total_water_row = $total_water_result->fetch_assoc();
    $water_consumed = $total_water_row['total_consumed'] ?? 0; // Fallback to 0 if null
} else {
    $water_consumed = 0; // Fallback to 0 on error
}

// Fetch the user's daily goal from the water_consumption table
$goal_query = "SELECT goal_amount FROM water_consumption WHERE user_id = ? LIMIT 1"; // Limit to ensure a single row
$goal_stmt = $conn->prepare($goal_query);
$goal_stmt->bind_param("i", $user_id);
$goal_stmt->execute();
$goal_result = $goal_stmt->get_result();

if ($goal_result) {
    $goal_row = $goal_result->fetch_assoc();
    $goal_amount = $goal_row['goal_amount'] ?? 2000; // Fallback to 2000 if null
} else {
    $goal_amount = 2000; // Fallback to 2000 on error
}

// Query to get the member ID from huan_fitness_users table
$query_member_id = "SELECT member_id FROM huan_fitness_users WHERE user_id = ?";
$stmt_member = $conn->prepare($query_member_id);
$stmt_member->bind_param("i", $user_id);
$stmt_member->execute();
$result_member = $stmt_member->get_result();
$member = $result_member->fetch_assoc();
$member_id = $member['member_id'];

// Query to get upcoming nutritionist meetings
$query_meetings = "SELECT date, start_time, end_time, nutritionist_category, request_status FROM dietary_consultations_details WHERE member_id = ? AND date >= CURDATE() ORDER BY date ASC";
$stmt_meetings = $conn->prepare($query_meetings);
$stmt_meetings->bind_param("i", $member_id);
$stmt_meetings->execute();
$result_meetings = $stmt_meetings->get_result();

// Fetching the meetings
$meetings = [];
while ($row = $result_meetings->fetch_assoc()) {
    $meetings[] = $row;
}

// Close the statements
$weight_stmt->close();
$water_stmt->close();
$exercise_stmt->close();
$total_water_stmt->close();
$goal_stmt->close();
$stmt_member->close();
$stmt_meetings->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/navBar.css">
    <link rel="stylesheet" href="css/dashboard1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
</head>
<body style = "background: linear-gradient(45deg, #f3e5f5, #e1f5fe);">
    <div class="container add" id="container">
        <div class="brand">
            <h3>Menu</h3>
            <a href="#" id="toggle"><i class="bi bi-list"></i></a>
        </div>
        <div class="user">
            <img src="css/img/dumbbell.png" alt="HuanFitness Logo">
            <div class="name">
                <h3 style = "color:white">HuanFitness</h3>
            </div>
        </div>
        
        <div class="navbar">
                <ul>
                    <li><a href="dashboard.php"><i class="bi bi-house"></i><span>DashBoard</span></a></li>
                    <li><a href="user_profile.php"><i class="bi bi-person-circle"></i><span>User Profile</span></a></li>
                    <li><a href="weight.php"><i class="bi bi-3-square-fill"></i><span>Body Weight Record</span></a></li>
                    <li><a href="MainWaterCon.php"><i class="bi bi-person-fill"></i><span>Water Consumption Record</span></a></li>
                    <li><a href="exercise_index.php"><i class="bi bi-folder"></i><span>Exercise Record</span></a></li>
                    
                    <!-- Dietary Consultation link -->
                    <li>
                        <a href="<?php
                            if (!$is_member && !$is_expired_member) {
                                echo 'membership.php';
                            } elseif ($is_expired_member) {
                                echo 'user_renew_membership.php';
                            } else {
                                echo 'consultation_category.php';
                            }
                        ?>">
                            <i class="bi bi-journal-medical"></i><span>Dietary Consultation</span>
                        </a>
                    </li>

                    <!-- Fitness Class Registration link -->
                    <li>
                        <a href="<?php
                            if (!$is_member && !$is_expired_member) {
                                echo 'membership.php';
                            } elseif ($is_expired_member) {
                                echo 'user_renew_membership.php';
                            } else {
                                echo 'fitness_class.php';
                            }
                        ?>">
                            <i class="bi bi-journal-medical"></i><span>Fitness Class Registration</span>
                        </a>
                    </li>

                    <li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i><span>Log Out</span></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <h2>Huan Fitness Dashboard</h2>
        <div class="first-row">
            <div class="card current-weight">
                <h2>Current Weight</h2>
                <p id="weight-value"><?= !empty($weight_values) ? end($weight_values) : 'No weight data available' ?> kg</p>
            </div>
            
            <div class="card daily-water-intake">
                <h2>Daily Water Intake</h2>
                <div>
                    <p>Goal: <span id="goal"><?= htmlspecialchars($goal_amount) ?></span> ml</p>
                    <p>Consumed: <span id="consumed"><?= htmlspecialchars($water_consumed) ?></span> ml</p>
                    <div class="progress-bar">
                        <div class="progress" id="progress" style="width: <?= $goal_amount > 0 ? ($water_consumed / $goal_amount) * 100 : 0 ?>%;"></div>
                    </div>
                </div>
            </div>

            <div class="card exercise-records">
                <h2>Today's Exercise Records</h2>
                <ul>
                    <?php if (!empty($today_exercises)): ?>
                        <?php foreach ($today_exercises as $exercise): ?>
                            <li><?= htmlspecialchars($exercise['name']) ?> - <?= htmlspecialchars($exercise['date']) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No exercises recorded for today.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <!-- Second Row: Charts -->
        <div class="second-row">
            <div class="chart-box">
                <canvas id="weightChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="waterChart"></canvas>
            </div>
        </div>
        
        <!-- Third Row: Modal Trigger Button -->
        <div class="third-row">
            <button class="open-modal-button" onclick="openModal()">Upcoming Meetings</button>
        </div>
    </div>


    <!-- Modal Structure -->
    <div id="editContainer" class="edit-container" style="display: none;">
        <div class="edit-content" style="background-color: white; padding: 50px 70px; border-radius: 10px; max-width: 600px; width: 100%; left:550px; top:50px;">
            <span class="close-btn" style="position: absolute; font-size: 40px;top: 10px; right: 20px; cursor: pointer;" onclick="closeModal()">×</span>
            <h2>Upcoming Nutritionist Meetings</h2><br><br>
            <div class="meeting-cards-container">
                <?php if (count($meetings) > 0): ?>
                    <?php foreach ($meetings as $meeting): ?>
                        <div class="meeting-card">
                            <h3><?php echo htmlspecialchars($meeting['date']); ?></h3>
                            <p><strong>Start Time:</strong> <?php echo htmlspecialchars($meeting['start_time']); ?></p>
                            <p><strong>End Time:</strong> <?php echo htmlspecialchars($meeting['end_time']); ?></p>
                            <p><strong>Dietary Category:</strong> <?php echo htmlspecialchars($meeting['nutritionist_category']); ?></p>
                            <p><strong>Status:</strong> 
                                <span class="<?php echo 'status-' . strtolower(htmlspecialchars($meeting['request_status'])); ?>">
                                    <?php echo htmlspecialchars($meeting['request_status']); ?>
                                </span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No upcoming nutritionist meetings scheduled.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    </div>
</div>



        </div>
    </div>

    <script>
        var toggle = document.getElementById("toggle");
		var container = document.getElementById("container");

		toggle.onclick = function() {
			container.classList.toggle('active');
		}

        // Chart.js for Weight Chart Left
const ctxWeight = document.getElementById('weightChart').getContext('2d');
const weightChart = new Chart(ctxWeight, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_reverse($weight_dates)) ?>,
        datasets: [{
            label: 'Weight (kg)',
            data: <?= json_encode(array_reverse($weight_values)) ?>,
            borderColor: 'rgba(0, 123, 255, 1)',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 20,
                    padding: 15
                }
            },
            tooltip: {
                enabled: true,
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: false,
                grid: {
                    color: 'rgba(0, 123, 255, 0.2)',
                    lineWidth: 1
                },
                title: {
                    display: true,
                    text: 'Weight (kg)',
                    font: {
                        size: 16
                    }
                }
            }
        }
    }
});

const ctxWater = document.getElementById('waterChart').getContext('2d');
    const waterChart = new Chart(ctxWater, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_reverse($water_dates)) ?>,
            datasets: [{
                label: 'Water Intake (mL)',
                data: <?= json_encode(array_reverse($water_intake_values)) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)', 
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5, 
                hoverBackgroundColor: 'rgba(54, 162, 235, 0.8)',
                hoverBorderColor: 'rgba(54, 162, 235, 1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(54, 162, 235, 0.2)',
                        lineWidth: 1
                    },
                    title: {
                        display: true,
                        text: 'Water Intake (mL)',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        }
    });

    function openModal() {
    document.getElementById('editContainer').style.display = 'block';
}

function closeModal() {
    document.getElementById('editContainer').style.display = 'none';
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('editContainer');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}



    </script>
</body>
</html>