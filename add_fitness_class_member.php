<?php

require_once('database.php'); 


if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = trim($_POST['member_id']);
    $category = trim($_POST['category']);

    
    $member_id = mysqli_real_escape_string($con, $member_id);
    $category = mysqli_real_escape_string($con, $category);
    
    // Check if member_id exists in huan_fitness_members
    $checkHuanFitnessMember = "SELECT * FROM huan_fitness_members WHERE member_id = '$member_id'";
    $result = mysqli_query($con, $checkHuanFitnessMember);

    // If the member ID does not exist in huan_fitness_members
    if (mysqli_num_rows($result) === 0) {
        echo "<script>alert('Member ID does not exist in huan_fitness_members. Please register the user account first.'); window.history.back();</script>";
        exit();
    }

    // Check if member_id already exists in fitness_class_member
    $checkFitnessClassMember = "SELECT * FROM fitness_class_member WHERE member_id = '$member_id'";
    $result = mysqli_query($con, $checkFitnessClassMember);

    // If the member ID already exists in fitness_class_member
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('User already exists in fitness_class_member.'); window.history.back();</script>";
        exit();
    }

    // Retrieve the fitness_class_id based on the category from fitness_class_details
    $getFitnessClassId = "SELECT fitness_class_id FROM fitness_class_details WHERE fitness_class_category = '$category' LIMIT 1";
    $fitnessClassResult = mysqli_query($con, $getFitnessClassId);

    // If the category is invalid and no fitness_class_id is found
    if (mysqli_num_rows($fitnessClassResult) === 0) {
        echo "<script>alert('Invalid category. Please ensure the category exists in fitness_class_details.'); window.history.back();</script>";
        exit();
    }

    
    $fitnessClassRow = mysqli_fetch_assoc($fitnessClassResult);
    $fitness_class_id = $fitnessClassRow['fitness_class_id'];

    // Insert into fitness_class_member
    $insertFitnessClassMember = "INSERT INTO fitness_class_member (member_id, fitness_class_id, category, request_status) VALUES ('$member_id', '$fitness_class_id', '$category', 'approved')";

    if (mysqli_query($con, $insertFitnessClassMember)) {
        echo "<script>alert('Member added successfully.'); window.location.href='Fitness_Class_Member.php';</script>";
    } else {
        echo "<script>alert('Error adding member: " . mysqli_error($con) . "'); window.history.back();</script>";
    }
}


mysqli_close($con);
?>
