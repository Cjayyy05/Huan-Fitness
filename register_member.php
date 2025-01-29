<?php
session_start();
include('db_conn.php'); // Ensure this includes your database connection script

// Function to get the next member ID
function getNextMemberId($con) {
    $query = "SELECT MAX(member_id) AS max_id FROM huan_fitness_members";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $max_id = $row['max_id'];

    if ($max_id) {
        $numeric_part = (int)substr($max_id, 1);
        return 'M' . str_pad($numeric_part + 1, 2, '0', STR_PAD_LEFT);
    }
    return 'M16';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['reg_date']) && !empty($_POST['reg_date'])) {
    $user_id = $_POST['user_id'];
    $reg_date = $_POST['reg_date'];

    // Calculate the expiration date (one year after registration date)
    $expr_date = date('Y-m-d', strtotime('+1 year', strtotime($reg_date)));
    $status = "active";

    // Generate the new member ID
    $member_id = getNextMemberId($conn);

    // Prepare an insert query to add to huan_fitness_members
    $insert_query = "INSERT INTO huan_fitness_members (member_id, regDate, exprDate, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if (!$stmt) {
        die("Error preparing insert query: " . $conn->error);
    }

    $stmt->bind_param("ssss", $member_id, $reg_date, $expr_date, $status);

    if ($stmt->execute()) {
        $update_query = "UPDATE huan_fitness_users SET member_id = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_query);

        if ($update_stmt) {
            $update_stmt->bind_param("ss", $member_id, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
            echo "<script>alert('Membership registered successfully.'); window.location.href = 'welcome.php';</script>";
        } else {
            echo "Error updating huan_fitness_users: " . $conn->error;
        }
    } else {
        echo "Error inserting membership details: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "<script>alert('Please select a registration date.'); window.history.back();</script>";
}

$conn->close();
?>