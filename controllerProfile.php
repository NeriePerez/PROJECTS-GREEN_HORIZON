<?php 
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    // Fetch user profile data
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $userData = $stmt->get_result()->fetch_assoc();

    // Fetch user rank from user_points table
    $rankStmt = $conn->prepare("SELECT user_rank 
            FROM (SELECT user_id, monthly_points, RANK() OVER (ORDER BY monthly_points DESC) AS user_rank
                FROM user_points WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE()) LIMIT 10) AS ranked
            WHERE user_id = ?");
    $rankStmt->bind_param("i", $user_id);
    $rankStmt->execute();
    $rankData = $rankStmt->get_result()->fetch_assoc();

    $user_rank = $rankData ? "Top " . $rankData['user_rank'] . " Eco Warrior": "Eco Warrior";


    //dashboard info fetch
    $challengesDone = $conn->query("SELECT COUNT(*) as total FROM daily_challenge WHERE user_id = $user_id AND daily_status = 'completed'")->fetch_assoc()['total'];

    $quizLevel = $conn->query("SELECT q.quiz_level FROM user_quiz uq 
    JOIN quiz q ON uq.quiz_id = q.quiz_id
    WHERE uq.user_id = $user_id 
    ORDER BY q.quiz_level DESC LIMIT 1")->fetch_assoc();

    $streakRow = $conn->query("SELECT current_streak, longest_streak FROM user_streaks WHERE user_id = $user_id")->fetch_assoc();
    $currentStreak = $streakRow ? $streakRow['current_streak'] : 0;
    $longestStreak = $streakRow ? $streakRow['longest_streak'] : 0; 

    $month = date('m');
    $year = date('Y');
    $pointsRow = $conn->query("SELECT monthly_points, overall_points FROM user_points WHERE user_id = $user_id AND MONTH(`date`) = $month AND YEAR(`date`) = $year ORDER BY `date` DESC LIMIT 1")->fetch_assoc();
    $monthlyPoints = $pointsRow ? $pointsRow['monthly_points'] : 0;
    $overallPoints = $pointsRow ? $pointsRow['overall_points'] : 0;

    
    //EDIT PROFILE
    if (isset($_POST['editProfile'])) {
        $user_id = intval($_POST['user_id']);
        $username = trim($_POST['username']);
        $bio = trim($_POST['bio']);
        $user_sex = $_POST['user_sex'];
        $current_profpic = $_POST['user_profpic']; 

        $profilePicPath = $current_profpic; 

        // Check if user removed profile pic
        if (isset($_POST['remove_profile_pic']) && $_POST['remove_profile_pic'] == "1") {
            $profilePicPath = ($user_sex == 'male') ? "uploads/user_profile/noProfileB.webp" : "uploads/user_profile/noProfileG.webp";
        }

        // Handle uploaded file
        if (isset($_FILES['userNew_profpic']) && $_FILES['userNew_profpic']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['userNew_profpic']['tmp_name'];
            $fileName = $_FILES['userNew_profpic']['name'];
            $fileSize = $_FILES['userNew_profpic']['size'];
            $fileType = $_FILES['userNew_profpic']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 5 * 1024 * 1024) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = 'uploads/user_profile/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $profilePicPath = $dest_path;
                }
            }
        }

        // Update the database
        $stmt = $conn->prepare("UPDATE user SET user_username = ?, user_bio = ?, user_profpic = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $username, $bio, $profilePicPath, $user_id);

        if ($stmt->execute()) {
            header("Location: profile.php?user_id=" . $user_id . "&profileEditSuccess=1");
            exit;
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
    }

?>