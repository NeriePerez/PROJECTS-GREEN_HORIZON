<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    // Check if user already has a challenge for today
    $check = $conn->prepare("SELECT * FROM daily_challenge WHERE user_id = ? AND assigned_date = CURDATE()");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        // Get a random challenge from challenge table
        $rand = $conn->query("SELECT challenge_id FROM challenges ORDER BY RAND() LIMIT 1");
        $challenge = $rand->fetch_assoc();

        // Assign it to the user
        $insert = $conn->prepare("INSERT INTO daily_challenge (user_id, challenge_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $challenge['challenge_id']);
        $insert->execute();
    }

    $updateOld = $conn->prepare("UPDATE daily_challenge SET daily_status = 'expired' WHERE daily_status = 'ongoing' AND assigned_date < CURDATE()");
    $updateOld->execute();


    // Check if user already has assigned quiz
    $checkQ = $conn->prepare("SELECT quiz_id FROM user_quiz WHERE user_id = ?");
    $checkQ->bind_param("i", $user_id);
    $checkQ->execute();
    $resultQ = $checkQ->get_result();

    if ($resultQ->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO user_quiz (user_id, quiz_id) VALUES (?, 1)");
        $insert->bind_param("i", $user_id);
        $insert->execute();
    } 

    // Check if user already has points entry for the current month and year
    $checkM = $conn->prepare("SELECT userPoints_id FROM user_points WHERE user_id = ? AND MONTH(`date`) = ? AND YEAR(`date`) = ?");
    $month = date('m');
    $year = date('Y');
    $checkM->bind_param("iii", $user_id, $month, $year);
    $checkM->execute();
    $resultM = $checkM->get_result();

    if ($resultM->num_rows === 0) {
        $overallPoints = $conn->prepare("SELECT overall_points FROM user_points WHERE user_id = ? ORDER BY `date` DESC LIMIT 1");
        $overallPoints->bind_param("i", $user_id);
        $overallPoints->execute();
        $overallPointsR = $overallPoints->get_result();
        if ($overallPointsR->num_rows === 1) {
            $row = $overallPointsR->fetch_assoc();
            $latestOverall = $row['overall_points'];

            $insert = $conn->prepare("INSERT INTO user_points (user_id, overall_points) VALUES (?, ?)");
            $insert->bind_param("ii", $user_id, $latestOverall);
            $insert->execute();
        } else {
            $insert = $conn->prepare("INSERT INTO user_points (user_id) VALUES (?)");
            $insert->bind_param("i", $user_id);
            $insert->execute();
        }
    } 

    // Check if user already has steak entry 
    $checkS = $conn->prepare("SELECT streak_id FROM user_streaks WHERE user_id = ?");
    $checkS->bind_param("i", $user_id);
    $checkS->execute();
    $resultS = $checkS->get_result();

    if ($resultS->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO user_streaks (user_id) VALUES (?)");
        $insert->bind_param("i", $user_id);
        $insert->execute();
    } 

    // Check if user streak continues 
    $checkSR = $conn->prepare("SELECT last_completion_date FROM user_streaks WHERE user_id = ?");
    $checkSR->bind_param("i", $user_id);
    $checkSR->execute();
    $resultSR = $checkSR->get_result();

    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));

    if (!($resultSR->num_rows === 0)) {
        $streakData = $resultSR->fetch_assoc();
        $lastDate = $streakData['last_completion_date'];

        if ($lastDate !== $yesterday) {
            $update = $conn->prepare("UPDATE user_streaks SET current_streak = 0 WHERE user_id = ?");
            $update->bind_param("i", $user_id);
            $update->execute();
        }
    } 
?>