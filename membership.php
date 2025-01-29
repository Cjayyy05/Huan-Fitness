<?php
include('db_conn.php');

session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="css/navBar.css">
    <link rel="stylesheet" href="css/membership.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <div class="container add" id="container">
        <div class="brand">
            <h3>Menu</h3>
            <a href="#" id="toggle"><i class="bi bi-list"></i></a>
        </div>
        <div class="user">
            <img src="css/img/dumbbell.png" alt="">
            <div class="name">
                <h3>HuanFitness</h3>
            </div>
        </div>
        <div class="navbar">
            <ul>
                <li><a href="dashboard.php"><i class="bi bi-house"></i><span>DashBoard</span></a></li>
                <li><a href="user_profile.php"><i class="bi bi-person-circle"></i><span>User Profile</span></a></li>
                <li><a href="weight.php"><i class="bi bi-3-square-fill"></i><span>Body Weight Record</span></a></li>
                <li><a href="#"><i class="bi bi-person-fill"></i><span>Water Consumption Record</span></a></li>
                <li><a href="exercise_index.php"><i class="bi bi-folder"></i><span>Exercise Record</span></a></li>
                <li><a href="consultation_category.php"><i class="bi bi-journal-medical"></i><span>Dietary Consultation</span></a></li>
                <li><a href="fitness_class.php"><i class="bi bi-journal-medical"></i><span>Fitness Class Registration</span></a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i><span>Log Out</span></a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
    <div class="membership-section">  
        <h2>Register as a Member</h2>
        <span class="close-btn" style="font-size:30px; position: absolute; top: 155px; right: 450px; cursor: pointer;" onclick="closeEdit()">×</span>
        
        <!-- Display Membership Benefits -->
        <div class="membership-benefits">
            <h3>Membership Benefits:</h3><br>
            <ul>
                <li>Access to nutritionist appointments</li><br>
                <li>Join exclusive fitness classes</li><br>
                <li>Memberships are valid for a one-year period from the date of registration.</li><br>
                <li>Huan Fitnesss membership per month: RM50</li>
            </ul>
        </div>

        <!-- Membership Registration Form -->
        <form id="membershipForm" method="POST" action="register_member.php" onsubmit="return confirmRegistration()">
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" readonly>

            <label for="reg_date">Registration Date</label>
            <input type="text" id="reg_date" name="reg_date" placeholder="Select a date">

            <button type="submit" class="membership-button">Register Membership</button>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            flatpickr("#reg_date", {
                dateFormat: "Y-m-d",
                defaultDate: "today",
                minDate: "today"
            });

            function closeEdit() {
                var editContainer = document.getElementById('editContainer');
                if (editContainer) {
                    editContainer.style.display = 'none';  
                }
                window.location.href = 'dashboard.php'; 
            }
        </script>

        <script>
            function confirmRegistration() {
                return confirm('Membership registration price: RM50. \nAre you confirm registration with the selected date?');
            }
        </script>
    </div>  
</div>
</body>
</html>
