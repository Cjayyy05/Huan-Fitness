<?php
// Database connection
require_once('database.php');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Start session
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {
    header('Location: login.php');
    exit();
}

function getNextConsultID($con) {
    $query = "SELECT MAX(consult_id) AS max_id FROM dietary_consultations_details";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id) {
        // Extract the numeric part and increment
        $numeric_part = (int)substr($max_id, 4); 
        return 'CONS' . str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT); 
    }
    return 'CONS001'; // Default value if no IDs exist
}

// Get user information
$user_sql = "SELECT user_id, member_id, username FROM huan_fitness_users WHERE user_id = ?";
if ($user_stmt = $con->prepare($user_sql)) {
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $user_stmt->close();
} else {
    die("Failed to prepare user query: " . $con->error);
}


$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["nutritionist"]) || empty($_POST["date"]) || empty($_POST["time"])) {
        $error_message = "Please fill in all required information.";
    } else {
        $member_id = isset($user_data['member_id']) ? $user_data['member_id'] : "TEST001"; // Temporary test value
        $nutritionist_id = $_POST["nutritionist"];
        $date = $_POST["date"];
        $time_slot = $_POST["time"];

        list($start_time, $end_time) = explode('-', $time_slot);

        $availability_query = "SELECT * FROM dietary_consultations_details 
                                WHERE date = ? 
                                AND (
                                   (start_time <= ? AND end_time > ?) OR 
                                   (start_time < ? AND end_time >= ?) OR 
                                   (start_time >= ? AND end_time <= ?)
                               )";
        if ($stmt = $con->prepare($availability_query)) {
            $stmt->bind_param("sssssss", $date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time);
            $stmt->execute();
            $availability_result = $stmt->get_result();

            if ($availability_result->num_rows > 0) {
                $error_message = "The selected time slot is not available.";
            } else {
                // Generate a new consult_id
                $consult_id = getNextConsultID($con);

                $insert_query = "INSERT INTO dietary_consultations_details (consult_id, Nutritionist_ID, nutritionist_category, member_id, date, start_time, end_time, request_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if ($insert_stmt = $con->prepare($insert_query)) {
                    $nutritionist_category = "Sport Nutritionist"; // Set nutritionist category
                    $request_status = "Pending"; // Set request status
                    $insert_stmt->bind_param("ssssssss", $consult_id, $nutritionist_id, $nutritionist_category, $member_id, $date, $start_time, $end_time, $request_status);
                    if ($insert_stmt->execute()) {
                        $success_message = "Appointment successful!";
                        echo "<script>
                                setTimeout(function() {
                                    window.location.href = 'consultation_category.php';
                                }, 1000);
                            </script>";
                    } else {
                        $error_message = "Appointment Failed, Please try again later. Error: " . $con->error;
                    }
                    $insert_stmt->close();
                } else {
                    $error_message = "Insertion statement preparation failed: " . $con->error;
                }
            }
            $stmt->close();
        } else {
            $error_message = "Query statement preparation failed: " . $con->error;
        }
    }
}

// Get nutritionist list
$nutritionist_sql = "SELECT Nutritionist_ID, Name FROM huan_fitness_nutritionist WHERE Category = 'Sports Nutritionist' ORDER BY Name";
$result = $con->query($nutritionist_sql);

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritionist Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(45deg, #f3e5f5, #e1f5fe);
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        input[type="button"],
        .back-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        input[type="button"] {
            background-color: #4CAF50;
            color: white;
            flex: 1;
        }

        .back-btn {
            background-color: #f44336;
            color: white;
            display: inline-block;
            flex: 1;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
            text-align: center;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #e8f5e9;
            border-radius: 4px;
            text-align: center;
        }

        .fee-input-group {
            display: flex;
            align-items: center;
        }

        .currency {
            padding: 8px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        #fee {
            border: 1px solid #ddd;
            border-left: none;
            border-radius: 0 4px 4px 0;
            padding: 8px;
            width: 100px;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <?php
    if (isset($error_message)) {
        echo "<div class='error'>" . htmlspecialchars($error_message) . "</div>";
    }
    if (isset($success_message)) {
        echo "<div class='success'>" . htmlspecialchars($success_message) . "</div>";
    }
    ?>

    <form id="appointmentForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Appointment with Sport Nutritionist</h2>

        <div class="form-group">
            <label for="date">Appointment Date:</label>
            <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
            <label for="time">Appointment Time:</label>
            <select id="time" name="time" required>
                <option value="">Choose a Time Slot</option>
                <?php
                $start_time = strtotime('08:30');
                $end_time = strtotime('20:30');
                
                while ($start_time <= $end_time - 3600) { 
                    $time_slot_start = date('H:i', $start_time);
                    $time_slot_end = date('H:i', strtotime('+1 hour', $start_time));
                    $time_slot = $time_slot_start . '-' . $time_slot_end;
                    echo '<option value="' . htmlspecialchars($time_slot) . '">' . htmlspecialchars($time_slot) . '</option>';
                    $start_time = strtotime('+1 hour', $start_time);
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nutritionist">Choose a Nutritionist:</label>
            <select id="nutritionist" name="nutritionist" required>
                <option value="">Select Nutritionist</option>
                <?php 
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['Nutritionist_ID']) . '">' . htmlspecialchars($row['Name']) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fee">Consultation Fee:</label>
            <div class="fee-input-group">
                <span class="currency">RM</span>
                <input type="text" id="fee" name="fee" value="20.00" readonly>
            </div>
        </div>

        <input type="hidden" name="confirm_payment" id="confirm_payment" value="false">

        <div class="button-group">
            <input type="button" id="submitBtn" value="Submit Appointment">
            <a href="consultation_category.php" class="back-btn">Back</a>
        </div>
    </form>

    <script>
    document.getElementById('submitBtn').addEventListener('click', function() {
        const form = document.getElementById('appointmentForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        if (confirm('Confirm appointment and pay RM20.00?\nClick OK to continue payment, click Cancel to return.')) {
            document.getElementById('confirm_payment').value = 'true';
            form.submit();
        } else {
            window.location.href = 'consultation_category.php';
        }
    });

    document.getElementById('date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>