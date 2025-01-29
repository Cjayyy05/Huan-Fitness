<?php

require_once('database.php');


if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


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
        
        $numeric_part = (int)substr($max_id, 4); 
        return 'CONS' . str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT); 
    }
    return 'CONS001'; 
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
        $member_id = isset($user_data['member_id']) ? $user_data['member_id'] : "TEST001"; 
        $nutritionist_id = $_POST["nutritionist"];
        $date = $_POST["date"];
        $time_slot = $_POST["time"];

        // Split time slot into start and end times
        list($start_time, $end_time) = explode('-', $time_slot);

        // Check overlapping appointments
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
               
                $consult_id = getNextConsultID($con);

                // Insert new appointment
                $insert_query = "INSERT INTO dietary_consultations_details (consult_id, Nutritionist_ID, nutritionist_category, member_id, date, start_time, end_time, request_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if ($insert_stmt = $con->prepare($insert_query)) {
                    $nutritionist_category = "Clinical Nutritionist"; 
                    $request_status = "Pending"; 
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
$nutritionist_sql = "SELECT Nutritionist_ID, Name FROM huan_fitness_nutritionist WHERE Category = 'Clinical Nutritionist' ORDER BY Name";
$result = $con->query($nutritionist_sql);


$con->close();
?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment with Nutritionist</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 5px 0;
            color: #666;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #2c3e50;
            font-weight: bold;
        }

        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .error {
            color: #e74c3c;
            background-color: #fde8e7;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            color: #27ae60;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

       
        @media (max-width: 768px) {
            form {
                padding: 20px;
                margin: 10px;
            }
        }
        .button-group {
        display: flex;
        flex-direction: column;
        align-items: center; 
        gap: 10px;
        margin-top: 20px;
    }

    .button-group input[type="submit"],
    .back-btn {
        width: 50%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .button-group input[type="submit"] {
        background-color: #3498db;
        color: white;
    }

    .button-group input[type="submit"]:hover {
        background-color: #2980b9;
    }

    .back-btn {
        
        color: black;
        display: inline-block;
    }

    .back-btn:hover {
        background-color: #7f8c8d;
    }

    
    @media (max-width: 768px) {
        .button-group input[type="submit"],
        .back-btn {
            width: 20%; 
        }
    }
    </style>
</head>
<body style="background: linear-gradient(45deg, #f3e5f5, #e1f5fe);">
    <?php
    if (isset($error_message)) {
        echo "<div class='error'>" . htmlspecialchars($error_message) . "</div>";
    }
    if (isset($success_message)) {
        echo "<div class='success'>" . htmlspecialchars($success_message) . "</div>";
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Appointment with Clinical Nutritionist</h2>

        <div class="form-group">
            <label for="date">Appointment Date:</label>
            <input type="date" id="date" name="date" required>
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
            <label for="nutritionist">Choose a Nutritionist</label>
            <select id="nutritionist" name="nutritionist" required>
                <option value="">Choose a Nutritionist</option>
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

        <div class="button-group">
    <input type="submit" value="Submit Appointment">
    <a href="consultation_category.php" class="back-btn">Back</a>
</div>
    </form>
</body>
</html>