<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //QUIZ CHECKER
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'])) {
        // Ensure user_id and quiz_id are sent
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $quiz_id = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;
        $answers = isset($_POST['answer']) ? $_POST['answer'] : [];
        $question_ids = isset($_POST['question_id']) ? $_POST['question_id'] : [];

        if ($user_id && $quiz_id && !empty($answers)) {
            // Fetch quiz level
            $resultQ = $conn->query("SELECT quiz_level FROM quiz WHERE quiz_id = $quiz_id")->fetch_assoc();

            $score = 0;

            // Loop through answered questions
            foreach ($question_ids as $index => $qid) {
                $selectedLetter = isset($answers[$index]) ? $answers[$index] : '';

                // Fetch correct answer and points for this question
                $stmt = $conn->prepare("SELECT correct_answer, question_points FROM quiz_question WHERE question_id = ?");
                $stmt->bind_param("i", $qid);
                $stmt->execute();
                $result = $stmt->get_result();
                $q = $result->fetch_assoc();

                if ($selectedLetter && $selectedLetter === $q['correct_answer']) {
                    $score += $q['question_points'];
                }
            }

            // Record completion date (YYYY-MM-DD)
            $date_completed = date('Y-m-d');

            // Update user_quiz table with score + completion date
            $update = $conn->prepare("
                UPDATE user_quiz 
                SET earned_points = ?, monthYear_completed = ? 
                WHERE user_id = ? AND quiz_id = ?
            ");
            $update->bind_param("isii", $score, $date_completed, $user_id, $quiz_id);
            $update->execute();

            $checkR = $conn->query("SELECT monthly_points, overall_points FROM user_points WHERE user_id = $user_id ORDER BY `date` DESC LIMIT 1")->fetch_assoc();
            $monthPoints = $checkR['monthly_points'] + $score;
            $overallPoints = $checkR['overall_points'] + $score;
            $month = date('m');
            $year = date('Y');
            $updateR = $conn->prepare("UPDATE user_points SET monthly_points = ?, overall_points = ? WHERE user_id = ? AND MONTH(`date`) = ? AND YEAR(`date`) = ?");
            $updateR->bind_param("iiiii", $monthPoints, $overallPoints, $user_id, $month, $year);
            $updateR->execute();

            // Assign next level if available
            $new_level = $resultQ['quiz_level'] + 1;
            $resultN = $conn->query("SELECT quiz_id FROM quiz WHERE quiz_level = $new_level")->fetch_assoc();

            if ($resultN && isset($resultN['quiz_id'])) {
                $next_quiz_id = $resultN['quiz_id'];

                // Prevent duplicate record for same user and quiz
                $check = $conn->prepare("SELECT * FROM user_quiz WHERE user_id = ? AND quiz_id = ?");
                $check->bind_param("ii", $user_id, $next_quiz_id);
                $check->execute();
                $existing = $check->get_result();

                if ($existing->num_rows === 0) {
                    $insert = $conn->prepare("INSERT INTO user_quiz (user_id, quiz_id) VALUES (?, ?)");
                    $insert->bind_param("ii", $user_id, $next_quiz_id);
                    $insert->execute();
                }
            }

            $quiz_level = $resultQ['quiz_level'];
            header("Location: index.php?user_id=$user_id&quiz_level=$quiz_level&quiz_score=$score");
            exit;
        } else {
            echo "<script>alert('No answers submitted or invalid user/quiz ID.'); window.location.href='index.php?user_id=$user_id';</script>";
            exit;
        }
    }

    //add post
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['challenge_post'])) {
        $user_id = intval($_POST['user_id']);
        $daily_id = intval($_POST['daily_id']);
        $postDetails_id = null; 
        $caption = $conn->real_escape_string($_POST['caption']);
        $completion_points = intval($_POST['points']); 
        $post_date = date("Y-m-d H:i:s");

        // 1️⃣ Handle image uploads
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Initialize image paths
        $images = ['img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => ''];

        for ($i = 1; $i <= 5; $i++) {
            if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES["image$i"]['tmp_name'];
                $fileName = time() . "_{$i}_" . basename($_FILES["image$i"]['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($fileTmp, $filePath)) {
                    $images["img$i"] = $filePath;
                } else {
                    $images["img$i"] = ''; // fallback
                }
            } else {
                $images["img$i"] = ''; // if not uploaded
            }
        }

        // 2️⃣ Insert into post_details
        $stmtDetails = $conn->prepare("INSERT INTO post_details (caption, img1, img2, img3, img4, img5) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtDetails->bind_param("ssssss", $caption, $images['img1'], $images['img2'], $images['img3'], $images['img4'], $images['img5']);
        
        if ($stmtDetails->execute()) {
            $postDetails_id = $stmtDetails->insert_id;
            $stmtDetails->close();

            // 3️⃣ Insert into challenge_completion_post
            $stmtPost = $conn->prepare("INSERT INTO challenge_completion_post (user_id, daily_id, postDetails_id, post_date, completion_points) VALUES (?, ?, ?, ?, ?)");
            $stmtPost->bind_param("iiisi", $user_id, $daily_id, $postDetails_id, $post_date, $completion_points);

            //updates the user_points
            $checkR = $conn->query("SELECT monthly_points, overall_points FROM user_points WHERE user_id = $user_id ORDER BY `date` DESC LIMIT 1")->fetch_assoc();
            $monthPoints = $checkR['monthly_points'] + $completion_points;
            $overallPoints = $checkR['overall_points'] + $completion_points;
            $month = date('m');
            $year = date('Y');
            $updateR = $conn->prepare("UPDATE user_points SET monthly_points = ?, overall_points = ? WHERE user_id = ? AND MONTH(`date`) = ? AND YEAR(`date`) = ?");
            $updateR->bind_param("iiiii", $monthPoints, $overallPoints, $user_id, $month, $year);
            $updateR->execute();

            //updates the status of the daily challenge
            $update = $conn->prepare("UPDATE daily_challenge SET daily_status = 'completed' WHERE daily_id = ?");
            $update->bind_param("i", $daily_id);
            $update->execute();

            if ($stmtPost->execute()) {
                $stmtPost->close();

                // Fetch the longest streak
                $streak = $conn->query("SELECT current_streak, longest_streak FROM user_streaks WHERE user_id = $user_id")->fetch_assoc();
                $newStreak = $streak['current_streak'] + 1;
                $today = date('Y-m-d');

                if ($streak) {
                    if($newStreak > $streak['longest_streak']) {
                        $update = $conn->prepare("UPDATE user_streaks SET current_streak = ?, longest_streak = ?, last_completion_date = ? WHERE user_id = ?");
                        $update->bind_param("iisi", $newStreak, $newStreak, $today, $user_id);
                        $update->execute();
                    } else {
                        $update = $conn->prepare("UPDATE user_streaks SET current_streak = ?, last_completion_date = ? WHERE user_id = ?");
                        $update->bind_param("isi", $newStreak, $today, $user_id);
                        $update->execute(); 
                    }
                } 

                // Redirect after successful post
                header("Location: index.php?user_id=$user_id&success=1");
                exit;
            } else {
                echo "Error saving post: " . $conn->error;
            }

        } else {
            echo "Error saving post details: " . $conn->error;
        }
    }

    // EDIT POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_post'])) {

        $user_id = intval($_POST['user_id']);
        $postDetails_id = intval($_POST['postDetails_id']);
        $caption = $conn->real_escape_string($_POST['caption']);

        // Get existing post images
        $old = $conn->query("SELECT img1, img2, img3, img4, img5 
                            FROM post_details 
                            WHERE postDetails_id = $postDetails_id")->fetch_assoc();

        // Images user decided to KEEP
        $existingImages = isset($_POST['existing_images']) ? $_POST['existing_images'] : [];

        // Prepare slots
        $images = [
            'img1' => $old['img1'],
            'img2' => $old['img2'],
            'img3' => $old['img3'],
            'img4' => $old['img4'],
            'img5' => $old['img5']
        ];

        // Map img slots → image input names
        $inputMap = [
            'img1' => 'image1',
            'img2' => 'image2',
            'img3' => 'image3',
            'img4' => 'image4',
            'img5' => 'image5'
        ];

        // 1️⃣ REMOVE OLD IMAGES THAT WERE DELETED
        foreach ($images as $slot => $oldPath) {

            // If user kept it → skip deletion
            if (in_array($oldPath, $existingImages)) {
                continue;
            }

            $images[$slot] = "";
        }

        // 2️⃣ UPLOAD NEW IMAGES
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        for ($i = 1; $i <= 5; $i++) {
            $input = "image$i";

            if (!empty($_FILES[$input]['name']) && $_FILES[$input]['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES[$input]['tmp_name'];
                $fileName = time() . "_{$i}_" . basename($_FILES[$input]['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmp, $filePath)) {
                    // Find first empty slot in $images
                    foreach ($images as $slotName => $imgPath) {
                        if (empty($images[$slotName])) {
                            $images[$slotName] = $filePath;
                            break; // stop after placing in first empty slot
                        }
                    }
                }
            }
        }

        // 3️⃣ UPDATE DATABASE
        $stmt = $conn->prepare("
            UPDATE post_details 
            SET caption=?, img1=?, img2=?, img3=?, img4=?, img5=?
            WHERE postDetails_id=?
        ");

        $stmt->bind_param(
            "ssssssi",
            $caption,
            $images['img1'], $images['img2'], $images['img3'],
            $images['img4'], $images['img5'],
            $postDetails_id
        );

        $stmt->execute();

        header("Location: profile.php?user_id=$user_id&updated=1");
        exit;
    }

    // DELETE POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
        $postDetails_id = intval($_POST['postDetails_id']);
        $daily_id = intval($_POST['daily_id']);
        $user_id = intval($_POST['user_id']);

        // Fetch post data to adjust points
        $post = $conn->query("SELECT post_likes, challenge_id FROM post_details WHERE postDetails_id = $postDetails_id")->fetch_assoc();
        $likes = intval($post['post_likes']);
        $challenge_id = intval($post['challenge_id']);

        // 1️⃣ Remove post
        $conn->query("DELETE FROM post_details WHERE postDetails_id = $postDetails_id");

        // 2️⃣ Subtract points from monthly/gross totals
        // Example table structure:
        // user_points: user_id, monthly_points, gross_points, challenges_done
        $conn->query("
            UPDATE user_points
            SET monthly_points = monthly_points - $likes,
                gross_points = gross_points - $likes,
                challenges_done = challenges_done - 1
            WHERE user_id = $user_id
        ");

        // 3️⃣ Optionally, delete likes from likes table if exists
        $conn->query("DELETE FROM post_likes WHERE post_id = $postDetails_id");

        header("Location: profile.php?user_id=$user_id&deleted=1");
        exit;
    }
?>
